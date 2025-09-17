<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4; 
            display: flex;
            justify-content: center; 
            align-items: flex-start;
            padding-top: 50px;
            margin: 0;
        }
        
        .container {
            background-color: #fff; 
            padding: 30px;
            border-radius: 8px; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
            width: 100%;
            max-width: 500px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
            border-bottom: 2px solid #007bff; 
            padding-bottom: 10px;
        }

        label {
            display: block;
            margin-top: 10px;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
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

        button {
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button[type="submit"] {
            background-color: #28a745;
            color: white;
        }

        button[type="submit"]:hover {
            background-color: #1e7e34;
        }

        .btn-back {
            margin-top: 5px;
            width: 100%;
            background-color: #007bff;
            color: white;
            text-align: center;
            display: inline-block;
            text-decoration: none;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }

        select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        background-color: #fff;
        font-size: 14px;
        color: #333;
        appearance: none; /* Quita el estilo por defecto del navegador */
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24'><path fill='gray' d='M7 10l5 5 5-5z'/></svg>");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 14px;
        }

        select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

    </style>
</head>
<body>

    <div class="container">
        <h1>Editar Producto</h1>
        
        <form method="POST" action="{{ route('admin.productos.update', $producto) }}">
            @csrf 
            @method('PUT')
            
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="{{ $producto->nombre }}" required>
            
            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo" class="form-select" required>
                <option value="">Seleccione un tipo</option>
                <option value="empanada" {{ $producto->tipo == 'empanada' ? 'selected' : '' }}>Empanada</option>
                <option value="papa_rellena" {{ $producto->tipo == 'papa_rellena' ? 'selected' : '' }}>Papa rellena</option>
            </select>


            <label for="tamaño">Tamaño:</label>
            <input type="text" id="tamaño" name="tamaño" value="{{ $producto->tamaño }}" required>

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" value="{{ $producto->precio }}" step="0.01" required>
            
            <button type="submit">Actualizar</button>
        </form>

        <!-- Botón para volver al Index -->
        <a href="{{ route('admin.productos.index') }}" class="btn-back">
            <button type="button" class="btn-back">Volver al listado</button>
        </a>
    </div>

</body>
</html>
