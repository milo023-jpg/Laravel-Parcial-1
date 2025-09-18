@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Informe de Ventas</h1>

    <!-- Filtros -->
    <form method="GET" action="{{ route('admin.reportes.ventas') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label>Fecha inicio</label>
            <input type="date" name="fecha_inicio" class="form-control">
        </div>
        <div class="col-md-3">
            <label>Fecha fin</label>
            <input type="date" name="fecha_fin" class="form-control">
        </div>
        <div class="col-md-2">
            <label>Tipo producto</label>
            <input type="text" name="tipo_producto" class="form-control" placeholder="Ej: empanada">
        </div>
        <div class="col-md-2">
            <label>Tipo cliente</label>
            <select name="tipo_cliente" class="form-control">
                <option value="">Todos</option>
                <option value="1">Frecuente</option>
                <option value="0">No frecuente</option>
            </select>
        </div>
        <div class="col-md-2">
            <label>Ciudad</label>
            <input type="text" name="ciudad" class="form-control">
        </div>
        <div class="col-12">
            <button class="btn btn-primary">Filtrar</button>
        </div>
    </form>

    <!-- Tabla de resultados -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Venta</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Productos</th>
                <th>Valor Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
                <tr>
                    <td>{{ $venta->id }}</td>
                    <td>{{ $venta->cliente->nombre }}</td>
                    <td>{{ $venta->fecha_venta }}</td>
                    <td>
                        <ul>
                            @foreach($venta->items as $item)
                                <li>{{ $item->producto->nombre }} (x{{ $item->cantidad }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>${{ number_format($venta->valor_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
