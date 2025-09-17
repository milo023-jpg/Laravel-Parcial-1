@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 1200px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Gestión de Clientes</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.clientes.create') }}" class="btn btn-primary">➕ Nuevo Cliente</a>
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

    {{-- Tabla de clientes --}}
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle shadow-sm">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo Documento</th>
                    <th>Número Documento</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Ciudad</th>
                    <th>Frecuente</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->id }}</td>
                    <td>{{ $cliente->nombre }}</td>
                    <td>{{ $cliente->tipo_documento }}</td>
                    <td>{{ $cliente->numero_documento }}</td>
                    <td>{{ $cliente->telefono }}</td>
                    <td>{{ $cliente->direccion }}</td>
                    <td>{{ $cliente->ciudad }}</td>
                    <td>{{ $cliente->frecuente ? 'Sí' : 'No' }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('admin.clientes.edit', $cliente->id) }}" class="btn btn-success btn-sm">✏️ Editar</a>
                        <form action="{{ route('admin.clientes.destroy', $cliente->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este cliente?')">
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

</div>
@endsection
