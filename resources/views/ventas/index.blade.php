<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Pedidos</title>
    
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

        /* Estilo del botón 'Registrar Pedido' */
        .button-new {
            display: inline-block;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .button-new:hover {
            background-color: #1e7e34;
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
            background-color: #28a745;
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
            background-color: #e8f5e8;
        }

        /* Alineación para columnas numéricas */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Estilo para el valor total */
        .valor-total {
            font-weight: bold;
            color: #28a745;
        }

        /* Estilo para el descuento */
        .descuento {
            color: #dc3545;
        }
    </style>
</head>
<body>

    <header>
        <h1>Gestionar Pedidos</h1>
        <div class="header-buttons">
            <a href="{{ route('pedidos.create') }}" class="button-new">Registrar Pedido</a>
            <a href="{{ route('menu') }}" class="button-menu">Regresar al menú</a>
        </div>
    </header>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border: 1px solid #c3e6cb; border-radius: 5px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border: 1px solid #f5c6cb; border-radius: 5px;">
            {{ session('error') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th class="text-center">Código</th>
                <th>Cliente</th>
                <th>Producto</th>
                <th class="text-center">Cantidad</th>
                <th class="text-right">Precio Unitario</th>
                <th class="text-right">Subtotal</th>
                <th class="text-center">Descuento (%)</th>
                <th class="text-right">Valor Total</th>
                <th class="text-center">Fecha/Hora</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedidos as $pedido)
            <tr>
                <td class="text-center">{{ $pedido->id }}</td>
                <td>{{ $pedido->cliente->nombre }}</td>
                <td>{{ $pedido->producto->nombre }}</td>
                <td class="text-center">{{ $pedido->cantidad }}</td>
                <td class="text-right">${{ number_format($pedido->precio_unitario, 2) }}</td>
                <td class="text-right">${{ number_format($pedido->subtotal, 2) }}</td>
                <td class="text-center descuento">
                    @if($pedido->descuento > 0)
                        {{ number_format($pedido->descuento, 2) }}%
                    @else
                        -
                    @endif
                </td>
                <td class="text-right valor-total">${{ number_format($pedido->valor_total, 2) }}</td>
                <td class="text-center">{{ $pedido->fecha_pedido }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($pedidos->isEmpty())
        <div style="text-align: center; padding: 50px; color: #6c757d; font-size: 1.1em;">
            <p>No hay pedidos registrados aún.</p>
            <p>¡Registra tu primer pedido usando el botón "Registrar Pedido"!</p>
        </div>
    @endif

</body>
</html>