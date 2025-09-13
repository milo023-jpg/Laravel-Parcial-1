<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Producto</title>

    <style>
        /* Estilos básicos para el cuerpo y el formulario */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4; /* Fondo gris claro */
            display: flex;
            flex-direction: column; /* Apila los elementos verticalmente */
            justify-content: center; /* Centra el contenido horizontalmente */
            align-items: center; /* Centra el contenido horizontalmente */
            padding-top: 50px;
            margin: 0;
        }

        form {
            background-color: #fff; /* Fondo blanco para el formulario */
            padding: 30px;
            border-radius: 8px; /* Bordes redondeados */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra ligera */
            width: 100%;
            max-width: 450px; /* Ancho máximo para que no se estire demasiado */
            margin-top: 25px; /* Añade un poco de espacio entre el h1 y el formulario */
        }

        /* Estilo del título */
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
            border-bottom: 2px solid #007bff; /* Línea de color azul bajo el título */
            padding-bottom: 10px;
        }

        /* Estilo para los campos de texto y número */
        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px; /* Espacio debajo de cada campo */
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Importante para que el padding no cambie el ancho total */
        }

        /* Estilo para las etiquetas (labels) */
        label {
            display: block; /* Ocupa toda la línea */
            margin-top: 10px;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        
        /* Contenedor para los botones para que estén en la misma fila */
        .button-container {
            display: flex;
            justify-content: space-around; /* Cambiado a 'space-around' para separar los botones */
            margin-top: 20px;
        }

        /* Estilo para los botones */
        .form-button {
            padding: 12px 20px;
            gap:5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 31%; /* Ajusta el ancho para tres botones */
            text-align: center;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease; /* Transición suave */
        }

        /* Estilo para el botón de "Guardar" */
        .save-button {
            background-color: #007bff; /* Fondo azul */
            color: white;
        }

        .save-button:hover {
            background-color: #0056b3; /* Azul más oscuro al pasar el ratón */
        }

        /* Estilo para el botón de "Atrás" */
        .back-button {
            background-color: #6c757d; /* Fondo gris para el botón de atrás */
            color: white;
        }

        .back-button:hover {
            background-color: #5a6268; /* Gris más oscuro al pasar el ratón */
        }

        /* Estilo para el nuevo botón "Cancelar" */
        .cancel-button {
            background-color: #dc3545; /* Fondo rojo */
            color: white;
        }

        .cancel-button:hover {
            background-color: #c82333; /* Rojo más oscuro al pasar el ratón */
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


</head>
<body>

    <h1>Nuevo Producto</h1>
    @if($errors->any())
        <div class="error-message">
            @foreach($errors->all() as $error)
                {{ $error }}
            @endforeach
        </div>
    @endif
    <form method="POST" action="{{ route('productos.store') }}">
        @csrf
        <label for="codigo">Código:</label>
        <input type="text" id="codigo" name="codigo" placeholder="Código" required>
        
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" placeholder="Nombre" required>
        
        <label for="cantidad">Cantidad:</label>
        <input type="number" id="cantidad" name="cantidad" placeholder="Cantidad" required>
        
        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" placeholder="Precio" step="0.01" required>
        
        <label for="descripcion">descripcion:</label>
        <input type="text" id="descripcion" name="descripcion" placeholder="Descripción" required>

        <div class="button-container">
            <a href="{{ route('productos.index') }}" class="form-button back-button">Atrás</a>
            <button type="submit" class="form-button save-button">Guardar</button>
        </div>
    </form>
</body>
</html>