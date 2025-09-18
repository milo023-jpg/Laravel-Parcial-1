<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use Carbon\Carbon;

class ReporteVentasController extends Controller
{
    public function index(Request $request)
    {
        // Filtros
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $tipoProducto = $request->input('tipo_producto');
        $tipoCliente = $request->input('tipo_cliente'); // 1 frecuente, 0 no frecuente
        $ciudad = $request->input('ciudad');

        // Query base con filtros
        $ventas = Venta::with(['cliente', 'items.producto'])
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_venta', [
                    Carbon::parse($fechaInicio)->startOfDay(),
                    Carbon::parse($fechaFin)->endOfDay()
                ]);
            })
            ->when($fechaInicio && !$fechaFin, function ($query) use ($fechaInicio) {
                $query->where('fecha_venta', '>=', Carbon::parse($fechaInicio)->startOfDay());
            })
            ->when(!$fechaInicio && $fechaFin, function ($query) use ($fechaFin) {
                $query->where('fecha_venta', '<=', Carbon::parse($fechaFin)->endOfDay());
            })
            ->when($tipoProducto, function ($query) use ($tipoProducto) {
                $query->whereHas('items.producto', function ($q) use ($tipoProducto) {
                    $q->where('tipo', 'like', "%$tipoProducto%");
                });
            })
            ->when($tipoCliente !== null && $tipoCliente !== '', function ($query) use ($tipoCliente) {
                $query->whereHas('cliente', function ($q) use ($tipoCliente) {
                    $q->where('frecuente', $tipoCliente);
                });
            })
            ->when($ciudad, function ($query) use ($ciudad) {
                $query->whereHas('cliente', function ($q) use ($ciudad) {
                    $q->where('ciudad', 'like', "%$ciudad%");
                });
            })
            ->get();

        // Totales (independientes de los filtros)
        $hoy = Carbon::today();
        $totalDia = Venta::whereBetween('fecha_venta', [$hoy->startOfDay(), $hoy->endOfDay()])
            ->sum('valor_total');

        $totalMes = Venta::whereMonth('fecha_venta', Carbon::now()->month)
            ->whereYear('fecha_venta', Carbon::now()->year)
            ->sum('valor_total');

        return view('reportes.ventas', compact('ventas', 'totalDia', 'totalMes'));
    }
}
