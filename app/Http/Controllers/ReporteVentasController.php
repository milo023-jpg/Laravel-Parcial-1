<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;

class ReporteVentasController extends Controller
{
    public function index(Request $request)
    {
        // Filtros
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $tipoProducto = $request->input('tipo_producto');
        $tipoCliente = $request->input('tipo_cliente'); // frecuente o no frecuente
        $ciudad = $request->input('ciudad');

        // Query base
        $ventas = Venta::with(['cliente', 'items.producto'])
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_venta', [$fechaInicio, $fechaFin]);
            })
            ->when($tipoProducto, function ($query) use ($tipoProducto) {
                $query->whereHas('items.producto', function ($q) use ($tipoProducto) {
                    $q->where('tipo', $tipoProducto);
                });
            })
            ->when($tipoCliente, function ($query) use ($tipoCliente) {
                $query->whereHas('cliente', function ($q) use ($tipoCliente) {
                    $q->where('frecuente', $tipoCliente);
                });
            })
            ->when($ciudad, function ($query) use ($ciudad) {
                $query->whereHas('cliente', function ($q) use ($ciudad) {
                    $q->where('direccion', 'like', "%$ciudad%");
                });
            })
            ->get();

        return view('admin.reportes.ventas', compact('ventas'));
    }
}
