@extends('layouts.app')

@section('content')
    <style>
        /* Estilos básicos para el cuerpo y el formulario */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 50px;
            margin: 0;
        }

        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            margin-top: 25px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        label {
            display: block;
            margin-top: 10px;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }

        .button-container {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .form-button {
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 31%;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .save-button {
            background-color: #007bff;
            color: white;
        }
        .save-button:hover {
            background-color: #0056b3;
        }

        .back-button {
            background-color: #6c757d;
            color: white;
        }
        .back-button:hover {
            background-color: #5a6268;
        }

        .cancel-button {
            background-color: #dc3545;
            color: white;
        }
        .cancel-button:hover {
            background-color: #c82333;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            max-width: 450px;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            max-width: 450px;
        }
    </style>

    <h1>Registrar Cliente</h1>

    {{-- Mensajes de error --}}
    @if ($errors->any())
        <div class="error-message">
            <strong>Ups!</strong> Hay algunos problemas con tus datos.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    {{-- Formulario --}}
    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf

        <label for="nombre">Nombre *</label>
        <input type="text" name="nombre" value="{{ old('nombre') }}" required>

        <label for="ciudad">Ciudad</label>
        <input type="text" name="ciudad" value="{{ old('ciudad') }}">

        <label for="tipo_documento">Tipo de Documento *</label>
        <select name="tipo_documento" required>
            <option value="">Seleccione...</option>
            <option value="CC" {{ old('tipo_documento') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
            <option value="TI" {{ old('tipo_documento') == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
            <option value="CE" {{ old('tipo_documento') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
            <option value="NIT" {{ old('tipo_documento') == 'NIT' ? 'selected' : '' }}>NIT</option>
            <option value="PASAPORTE" {{ old('tipo_documento') == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
        </select>

        <label for="numero_documento">Número de Documento *</label>
        <input type="text" name="numero_documento" value="{{ old('numero_documento') }}" required>

        <label for="telefono">Teléfono</label>
        <input type="text" name="telefono" value="{{ old('telefono') }}">

        <label for="direccion">Dirección</label>
        <textarea name="direccion">{{ old('direccion') }}</textarea>

        <label>
            <input type="checkbox" name="frecuente" value="1" {{ old('frecuente') ? 'checked' : '' }}>
            Cliente frecuente
        </label>

        <div class="button-container">
            <a href="{{ route('clientes.index') }}" class="form-button back-button">Atrás</a>
            <button type="reset" class="form-button cancel-button">Cancelar</button>
            <button type="submit" class="form-button save-button">Guardar</button>
        </div>
    </form>
@endsection
