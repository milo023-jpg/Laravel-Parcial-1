<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Productos</title>
    
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }

        header {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between; /* Alinea el título y los botones en extremos opuestos */
            align-items: center;
        }

        h1 {
            color: #333;
        }

        .header-buttons {
            display: flex;
            gap: 10px;
        }

        /* Estilo del botón 'Nuevo Producto' */
        .button-new {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .button-new:hover {
            background-color: #0056b3;
        }

        /* Estilo del botón 'Regresar al menú' */
        .button-menu {
            display: inline-block;
            padding: 10px 15px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .button-menu:hover {
            background-color: #5a6268;
        }

        /* Estilos de la tabla */
        table {
            width: 100%;
            border-collapse: collapse; /* Elimina los bordes dobles de las celdas */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        /* Encabezados de la tabla */
        th {
            background-color: #007bff;
            color: white;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 2px solid #ddd;
        }

        /* Celdas de datos */
        td {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
        }

        /* Efecto cebra: da color a las filas pares para mejorar la lectura */
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Estilo al pasar el ratón por la fila */
        tr:hover {
            background-color: #e0f7ff;
        }

        /* Estilos para la columna de acciones (botones) */
        .actions {
            white-space: nowrap; /* Mantiene los botones en una sola línea */
        }

        /* Estilo del enlace 'Editar' */
        .button-edit {
            color: #28a745; /* Verde */
            text-decoration: none;
            margin-right: 10px;
            font-weight: bold;
        }

        .button-edit:hover {
            text-decoration: underline;
        }

        /* Estilo del formulario de eliminar para mostrarlo en línea */
        .form-delete {
            display: inline;
        }

        /* Estilo del botón 'Eliminar' */
        .button-delete {
            background: none;
            border: none;
            padding: 0;
            margin: 0;
            cursor: pointer;
            color: #dc3545; /* Rojo */
            font-weight: bold;
            text-decoration: underline;
        }

        .button-delete:hover {
            color: #bd2130;
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

    <header>
        <h1>Gestionar Productos</h1>
        <div class="header-buttons">
            <a href="{{ route('productos.create') }}" class="button-new">Nuevo Producto</a>
            <a href="{{ route('menu') }}" class="button-menu">Regresar al menú</a>
        </div>
    </header>
    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border: 1px solid #c3e6cb; border-radius: 5px; max-width: 100%;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border: 1px solid #f5c6cb; border-radius: 5px; max-width: 100%;">
            {{ session('error') }}
        </div>
    @endif
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
            <tr>
                <td>{{ $producto->codigo }}</td>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->cantidad }}</td>
                <td>{{ $producto->precio }}</td>
                <td class="actions">
                    <a href="{{ route('productos.edit', $producto->codigo) }}" class="button-edit">Editar</a>
                    <form action="{{ route('productos.destroy', $producto->codigo) }}" method="POST" class="form-delete" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="button-delete">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>