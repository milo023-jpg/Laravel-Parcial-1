@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Informe de Ventas</h1>

    <!-- Filtros -->
     
    <!-- Botón volver al menú -->
    <div class="mt-3">
        <a href="{{ route('admin.index') }}" class="btn btn-secondary">⬅️ Menú Principal</a>
    </div>
    <form method="GET" action="{{ route('admin.reportes.ventas') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label>Fecha inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
        </div>
        <div class="col-md-3">
            <label>Fecha fin</label>
            <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
        </div>
        <div class="col-md-2">
            <label>Tipo producto</label>
            <input type="text" name="tipo_producto" class="form-control" placeholder="Ej: empanada" value="{{ request('tipo_producto') }}">
        </div>
        <div class="col-md-2">
            <label>Tipo cliente</label>
            <select name="tipo_cliente" class="form-control">
                <option value="">Todos</option>
                <option value="1" {{ request('tipo_cliente')==='1' ? 'selected' : '' }}>Frecuente</option>
                <option value="0" {{ request('tipo_cliente')==='0' ? 'selected' : '' }}>No frecuente</option>
            </select>
        </div>
        <div class="col-md-2">
            <label>Ciudad</label>
            <input type="text" name="ciudad" class="form-control" value="{{ request('ciudad') }}">
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
                <th>Ciudad</th>
                <th>Fecha</th>
                <th>Productos</th>
                <th>Valor Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ventas as $venta)
                <tr>
                    <td>{{ $venta->id }}</td>
                    <td>{{ $venta->cliente->nombre ?? 'Sin cliente' }}</td>
                    <td>{{ $venta->cliente->ciudad ?? '-' }}</td>
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
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay resultados</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
