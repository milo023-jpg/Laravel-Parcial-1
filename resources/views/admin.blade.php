<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empanadas y Más | Administrador</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            padding-top: 50px;
        }

        h1 {
            color: #333;
            margin-bottom: 50px;
            text-align: center;
            font-size: 2.5em;
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
            width: 100%;
            max-width: 800px;
        }

        .menu-container {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            justify-content: center;
            align-items: stretch;
            width: 100%;
            max-width: 900px;
        }

        .menu-card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 250px;
            height: 200px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            color: #333;
            font-size: 1.2em;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
            border: 2px solid transparent;
        }

        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 18px rgba(0, 0, 0, 0.2);
            background-color: #007bff;
            color: white;
            border: 2px solid #0056b3;
        }

        .menu-card h2 {
            margin: 0;
            font-size: 1.4em;
        }

        .menu-card p {
            margin-top: 10px;
            font-size: 0.9em;
            color: inherit;
        }
    </style>
</head>
<body>
    <h1>Empanadas y Más | Administrador</h1>
    
    <div class="menu-container">
        <a href="{{ route('admin.productos.index') }}" class="menu-card">
            <h2>Productos</h2>
            <p>Gestionar productos del inventario</p>
        </a>
        
        <a href="{{ route('admin.clientes.index') }}" class="menu-card">
            <h2>Clientes</h2>
            <p>Administrar información de clientes</p>
        </a>
        
        <a href="{{ route('admin.ventas.index') }}" class="menu-card">
            <h2>Ventas</h2>
            <p>Ver informes y registros de ventas</p>
        </a>
    </div>
</body>
</html>
