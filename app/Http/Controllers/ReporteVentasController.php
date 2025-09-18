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
        $fechaInicio   = $request->input('fecha_inicio');
        $fechaFin      = $request->input('fecha_fin');
        $tipoProducto  = $request->input('tipo_producto');
        $tipoCliente   = $request->input('tipo_cliente'); // 1 frecuente, 0 no frecuente
        $ciudad        = $request->input('ciudad');

        // Query base con filtros
        $query = Venta::with(['cliente', 'items.producto'])
            ->when($fechaInicio && $fechaFin, function ($q) use ($fechaInicio, $fechaFin) {
                $q->whereBetween('fecha_venta', [
                    Carbon::parse($fechaInicio)->startOfDay(),
                    Carbon::parse($fechaFin)->endOfDay()
                ]);
            })
            ->when($fechaInicio && !$fechaFin, function ($q) use ($fechaInicio) {
                $q->where('fecha_venta', '>=', Carbon::parse($fechaInicio)->startOfDay());
            })
            ->when(!$fechaInicio && $fechaFin, function ($q) use ($fechaFin) {
                $q->where('fecha_venta', '<=', Carbon::parse($fechaFin)->endOfDay());
            })
            ->when($tipoProducto, function ($q) use ($tipoProducto) {
                $q->whereHas('items.producto', function ($sub) use ($tipoProducto) {
                    $sub->where('tipo', 'like', "%$tipoProducto%");
                });
            })
            ->when($tipoCliente !== null && $tipoCliente !== '', function ($q) use ($tipoCliente) {
                $q->whereHas('cliente', function ($sub) use ($tipoCliente) {
                    $sub->where('frecuente', $tipoCliente);
                });
            })
            ->when($ciudad, function ($q) use ($ciudad) {
                $q->whereHas('cliente', function ($sub) use ($ciudad) {
                    $sub->where('ciudad', 'like', "%$ciudad%");
                });
            });

        // Obtener ventas filtradas
        $ventas = $query->get();

        // Totales usando el mismo query base (con filtros)
        $totalDia = (clone $query)
            ->whereDate('fecha_venta', Carbon::today()->toDateString())
            ->sum('valor_total');

        $totalMes = (clone $query)
            ->whereYear('fecha_venta', Carbon::now()->year)
            ->whereMonth('fecha_venta', Carbon::now()->month)
            ->sum('valor_total');

        return view('reportes.ventas', compact('ventas', 'totalDia', 'totalMes'));
}
}