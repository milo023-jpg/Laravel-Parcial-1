@extends('layouts.app')

@section('content')
    <form action="{{ route('admin.clientes.store') }}" method="POST" class="p-4 bg-white rounded shadow" style="width: 100%; max-width: 450px;">
        @csrf

        <h1 class="text-center mb-4 border-bottom pb-2">Registrar Cliente</h1>

        {{-- Mensajes --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Ups!</strong> Hay errores en tus datos.
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Campos --}}
        <label>Nombre *</label>
        <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control mb-2" required>

        <label>Tipo de Documento *</label>
        <select name="tipo_documento" class="form-select mb-2" required>
            <option value="">Seleccione...</option>
            <option value="CC" {{ old('tipo_documento') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
            <option value="TI" {{ old('tipo_documento') == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
            <option value="CE" {{ old('tipo_documento') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
            <option value="NIT" {{ old('tipo_documento') == 'NIT' ? 'selected' : '' }}>NIT</option>
            <option value="PASAPORTE" {{ old('tipo_documento') == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
        </select>

        <label>Número de Documento *</label>
        <input type="text" name="numero_documento" value="{{ old('numero_documento') }}" class="form-control mb-2" required>

        <label>Teléfono</label>
        <input type="text" name="telefono" value="{{ old('telefono') }}" class="form-control mb-2">

        <label>Dirección</label>
        <textarea name="direccion" class="form-control mb-2">{{ old('direccion') }}</textarea>

        <label>Ciudad</label>
        <input type="text" name="ciudad" value="{{ old('ciudad') }}" class="form-control mb-2">

        <div class="form-check mb-3">
            <input type="checkbox" name="frecuente" value="1" class="form-check-input" id="frecuente" {{ old('frecuente') ? 'checked' : '' }}>
            <label class="form-check-label" for="frecuente">Cliente frecuente</label>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">Atrás</a>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
@endsection

