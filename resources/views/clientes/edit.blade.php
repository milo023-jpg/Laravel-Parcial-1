@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-sm w-100" style="max-width: 500px;">
        <div class="card-header text-center bg-primary text-white">
            <h2 class="mb-0">Editar Cliente</h2>
        </div>
        <div class="card-body">

            {{-- Mensajes de error --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Mensaje de éxito --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.clientes.update', $cliente->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $cliente->nombre) }}" required>
                </div>

                <div class="mb-3">
                    <label for="tipo_documento" class="form-label">Tipo de Documento *</label>
                    <input type="text" name="tipo_documento" class="form-control" value="{{ old('tipo_documento', $cliente->tipo_documento) }}" required>
                </div>

                <div class="mb-3">
                    <label for="numero_documento" class="form-label">Número de Documento *</label>
                    <input type="text" name="numero_documento" class="form-control" value="{{ old('numero_documento', $cliente->numero_documento) }}" required>
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $cliente->telefono) }}">
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $cliente->direccion) }}">
                </div>

                <div class="mb-3">
                    <label for="ciudad" class="form-label">Ciudad</label>
                    <input type="text" name="ciudad" class="form-control" value="{{ old('ciudad', $cliente->ciudad) }}">
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="frecuente" class="form-check-input" id="frecuente" value="1" {{ old('frecuente', $cliente->frecuente) ? 'checked' : '' }}>
                    <label class="form-check-label" for="frecuente">Cliente frecuente</label>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('admin.clientes.index') }}" class="btn btn-danger">Cancelar</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
