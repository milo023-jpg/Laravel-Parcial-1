@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nuevo Producto</h1>

    <form action="{{ route('admin.productos.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" required>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo</label>
            <select name="tipo" id="tipo" class="form-select" required>
                <option value="" disabled selected>Seleccione un tipo</option>
                <option value="empanada" {{ old('tipo')=='empanada' ? 'selected' : '' }}>Empanada</option>
                <option value="papa_rellena" {{ old('tipo')=='papa_rellena' ? 'selected' : '' }}>Papa rellena</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="tamaño" class="form-label">Tamaño</label>
            <input type="text" name="tamaño" id="tamaño" class="form-control" value="{{ old('tamaño') }}" required>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" step="0.01" name="precio" id="precio" class="form-control" value="{{ old('precio') }}" required>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
