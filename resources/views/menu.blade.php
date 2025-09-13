<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventarios</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        h1 {
            color: #333;
            margin-bottom: 50px;
            text-align: center;
            font-size: 2.5em;
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
        }

        .menu-container {
            display: flex;
            gap: 30px;
            justify-content: center;
            align-items: center;
        }

        .menu-option {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 250px;
            height: 200px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            color: #333;
            font-size: 1.3em;
            font-weight: bold;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
            border: 2px solid transparent;
        }

        .menu-option:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            background-color: #007bff;
            color: white;
            border: 2px solid #0056b3;
        }

        .menu-option:active {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <h1>Gestión de Inventarios</h1>
    
    <div class="menu-container">
        <a href="{{ route('productos.index') }}" class="menu-option">
            Gestionar Productos
        </a>
        
        <a href="{{ route('clientes.index') }}" class="menu-option">
            Gestionar Clientes
        </a>
        
        <a href="{{ route('pedidos.index') }}" class="menu-option">
            Gestionar Pedidos
        </a>
    </div>
</body>
</html>