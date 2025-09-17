<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }

        header {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h1 {
            color: #333;
            margin: 0;
        }

        .header-buttons {
            display: flex;
            gap: 10px;
        }

        .button-new, .button-menu {
            display: inline-block;
            padding: 10px 15px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 14px;
        }

        .button-new { background-color: #007bff; }
        .button-new:hover { background-color: #0056b3; }

        .button-menu { background-color: #6c757d; }
        .button-menu:hover { background-color: #5a6268; }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
        }

        .table-container { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #e0f7ff; }

        .actions { white-space: nowrap; }

        .button-edit {
            color: #28a745;
            text-decoration: none;
            margin-right: 10px;
            font-weight: bold;
        }
        .button-edit:hover { text-decoration: underline; }

        .form-delete { display: inline; }
        .button-delete {
            background: none;
            border: none;
            padding: 0;
            margin: 0;
            cursor: pointer;
            color: #dc3545;
            font-weight: bold;
            text-decoration: underline;
        }
        .button-delete:hover { color: #bd2130; }
    </style>
</head>
<body>
    <header>
        <h1>Gestión de Clientes</h1>
        <div class="header-buttons">
            <a href="{{ route('clientes.create') }}" class="button-new">➕ Nuevo Cliente</a>
            <a href="{{ route('menu') }}" class="button-menu">⬅️ Menú Principal</a>
        </div>
    </header>

    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="error-message">{{ session('error') }}</div>
    @endif

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo Documento</th>
                    <th>Número Documento</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Ciudad</th>
                    <th>Frecuente</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->id }}</td>
                    <td>{{ $cliente->nombre }}</td>
                    <td>{{ $cliente->tipo_documento }}</td>
                    <td>{{ $cliente->numero_documento }}</td>
                    <td>{{ $cliente->telefono }}</td>
                    <td>{{ $cliente->direccion }}</td>
                    <td>{{ $cliente->ciudad }}</td>
                    <td>{{ $cliente->frecuente ? 'Sí' : 'No' }}</td>
                    <td class="actions">
                        <a href="{{ route('clientes.edit', $cliente->id) }}" class="button-edit">✏️ Editar</a>
                        <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="form-delete" onsubmit="return confirm('¿Seguro que deseas eliminar este cliente?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button-delete">🗑️ Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
