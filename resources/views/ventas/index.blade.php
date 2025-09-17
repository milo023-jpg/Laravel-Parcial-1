@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 1400px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Gestión de Ventas</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.ventas.create') }}" class="btn btn-primary">➕ Nueva Venta</a>
            <a href="{{ route('menu') }}" class="btn btn-secondary">⬅️ Menú Principal</a>
        </div>
    </div>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Tabla de ventas --}}
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle shadow-sm">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Productos</th>
                    <th>Subtotal</th>
                    <th>Descuento</th>
                    <th>Valor Total</th>
                    <th>Fecha Venta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventas as $venta)
                <tr>
                    <td>{{ $venta->id }}</td>
                    <td>{{ $venta->cliente->nombre }}</td>
                    <td>
                        <div class="productos-detalle">
                            @foreach($venta->detalles as $detalle)
                                <div class="producto-item mb-2 p-2 border rounded bg-light">
                                    <strong>{{ $detalle->producto->nombre }}</strong>
                                    <div class="text-muted small">
                                        <span>Cantidad: {{ $detalle->cantidad }}</span> | 
                                        <span>Precio Unit.: ${{ number_format($detalle->precio_unitario, 0, ',', '.') }}</span> | 
                                        <span>Subtotal: ${{ number_format($detalle->cantidad * $detalle->precio_unitario, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        <strong>${{ number_format($venta->subtotal, 0, ',', '.') }}</strong>
                    </td>
                    <td>
                        @if($venta->descuento > 0)
                            <span class="text-success">${{ number_format($venta->descuento, 0, ',', '.') }}</span>
                        @else
                            <span class="text-muted">$0</span>
                        @endif
                    </td>
                    <td>
                        <strong class="text-primary fs-5">${{ number_format($venta->valor_total, 0, ',', '.') }}</strong>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y H:i') }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('admin.ventas.show', $venta->id) }}" class="btn btn-info btn-sm">👁️ Ver</a>
                        <a href="{{ route('admin.ventas.edit', $venta->id) }}" class="btn btn-success btn-sm">✏️ Editar</a>
                        <form action="{{ route('admin.ventas.destroy', $venta->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar esta venta?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">🗑️ Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Paginación si existe --}}
    @if(isset($ventas) && method_exists($ventas, 'links'))
        <div class="d-flex justify-content-center mt-4">
            {{ $ventas->links() }}
        </div>
    @endif

</div>

<style>
.productos-detalle {
    max-width: 300px;
}

.producto-item {
    background-color: #f8f9fa !important;
    border: 1px solid #dee2e6 !important;
}

.table td {
    vertical-align: middle;
}

.table-responsive {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.375rem;
    overflow: hidden;
}

.table thead th {
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
}
</style>
@endsection