@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Gestión de Productos</h1>

    {{-- Mensajes de éxito o error --}}
    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('productos.create') }}" class="btn btn-primary mb-3">Nuevo Producto</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Tamaño</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productos as $p)
            <tr>
                <td>{{ $p->nombre }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $p->tipo)) }}</td>
                <td>{{ $p->tamaño }}</td>
                <td>${{ number_format($p->precio, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('productos.edit', $p) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('productos.destroy', $p) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro de eliminar este producto?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No hay productos registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
