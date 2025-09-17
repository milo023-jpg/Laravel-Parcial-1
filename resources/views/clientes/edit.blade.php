@extends('layouts.app')

@section('content')
    <h1>Editar Cliente</h1>

    {{-- Mensajes de error --}}
    @if ($errors->any())
        <div class="error-message">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required>

        <label for="tipo_documento">Tipo de Documento</label>
        <input type="text" name="tipo_documento" value="{{ old('tipo_documento', $cliente->tipo_documento) }}" required>

        <label for="numero_documento">Número de Documento</label>
        <input type="text" name="numero_documento" value="{{ old('numero_documento', $cliente->numero_documento) }}" required>

        <label for="telefono">Teléfono</label>
        <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" required>

        <label for="direccion">Dirección</label>
        <input type="text" name="direccion" value="{{ old('direccion', $cliente->direccion) }}" required>

        <label for="ciudad">Ciudad</label>
        <input type="text" name="ciudad" value="{{ old('ciudad', $cliente->ciudad) }}" required>

        <label for="frecuente">¿Cliente Frecuente?</label>
        <input type="checkbox" name="frecuente" value="1" {{ old('frecuente', $cliente->frecuente) ? 'checked' : '' }}>

        <div class="button-container">
            <button type="submit" class="form-button save-button">Actualizar</button>
            <a href="{{ route('clientes.index') }}" class="form-button back-button">Atrás</a>
            <a href="{{ route('clientes.index') }}" class="form-button cancel-button">Cancelar</a>
        </div>
    </form>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            justify-content: center;
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
        input[type="number"] {
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
            gap: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 31%;
            text-align: center;
            text-decoration: none;
            display: inline-block;
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
@endsection
