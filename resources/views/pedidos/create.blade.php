<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Venta</title>

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
            border-bottom: 2px solid #28a745; /* Línea de color verde bajo el título */
            padding-bottom: 10px;
        }

        /* Estilo para los campos de texto, número y select */
        input[type="text"],
        input[type="number"],
        select {
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
            width: 45%; /* Ajusta el ancho para dos botones */
            text-align: center;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease; /* Transición suave */
        }

        /* Estilo para el botón de "Registrar Venta" */
        .save-button {
            background-color: #28a745; /* Fondo verde */
            color: white;
        }

        .save-button:hover {
            background-color: #1e7e34; /* Verde más oscuro al pasar el ratón */
        }

        /* Estilo para el botón de "Atrás" */
        .back-button {
            background-color: #6c757d; /* Fondo gris para el botón de atrás */
            color: white;
        }

        .back-button:hover {
            background-color: #5a6268; /* Gris más oscuro al pasar el ratón */
        }

        /* Estilo para el select */
        select {
            background-image: url("data:image/svg+xml;utf8,<svg fill='gray' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 18px;
            padding-right: 35px; /* espacio para la flecha */
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        select:focus {
            outline: none;
            border-color: #28a745;
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
        }

        /* Campo calculado automáticamente */
        .readonly-field {
            background-color: #e9ecef;
            color: #6c757d;
        }

        /* Estilo para mensajes de error */
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            max-width: 450px;
        }
    </style>

    <script>
        function calcularTotal() {
            const cantidad = parseFloat(document.getElementById('cantidad_vendida').value) || 0;
            const precio = parseFloat(document.getElementById('precio_unitario').value) || 0;
            const total = cantidad * precio;
            document.getElementById('total').value = total.toFixed(2);
        }

        function cargarPrecioProducto() {
            const select = document.getElementById('producto_id');
            const selectedOption = select.options[select.selectedIndex];
            const precio = selectedOption.getAttribute('data-precio');
            
            if (precio && precio !== '') {
                document.getElementById('precio_unitario').value = parseFloat(precio).toFixed(2);
                calcularTotal();
            } else {
                // Si no se selecciona producto, limpiar campos
                document.getElementById('precio_unitario').value = '';
                document.getElementById('total').value = '';
            }
        }

        // Validación antes de enviar el formulario
        function validarFormulario(event) {
            const productoId = document.getElementById('producto_id').value;
            const cantidad = document.getElementById('cantidad_vendida').value;
            const precio = document.getElementById('precio_unitario').value;

            if (!productoId) {
                alert('Por favor seleccione un producto');
                event.preventDefault();
                return false;
            }

            if (!cantidad || cantidad <= 0) {
                alert('Por favor ingrese una cantidad válida');
                event.preventDefault();
                return false;
            }

            if (!precio || precio <= 0) {
                alert('Error: No se pudo cargar el precio del producto');
                event.preventDefault();
                return false;
            }

            return true;
        }

        // Inicializar cuando la página cargue
        document.addEventListener('DOMContentLoaded', function() {
            // Si ya hay un producto seleccionado (por ejemplo, después de un error de validación)
            const select = document.getElementById('producto_id');
            if (select.value) {
                cargarPrecioProducto();
            }

            // Agregar validación al formulario
            const form = document.querySelector('form');
            form.addEventListener('submit', validarFormulario);
        });
    </script>

</head>
<body>

    <h1>Registrar Venta</h1>
    
    @if(session('error'))
        <div class="error-message">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('ventas.store') }}">
        @csrf
        
        <label for="producto_id">Producto:</label>
        <select id="producto_id" name="producto_id" required onchange="cargarPrecioProducto()">
            <option value="">Seleccione un producto...</option>
            @foreach($productos as $producto)
                <option value="{{ $producto->codigo }}" data-precio="{{ $producto->precio }}" {{ old('producto_id') == $producto->codigo ? 'selected' : '' }}>
                    {{ $producto->nombre }} (Stock: {{ $producto->cantidad }})
                </option>
            @endforeach
        </select>
        
        <label for="cantidad_vendida">Cantidad:</label>
        <input type="number" id="cantidad_vendida" name="cantidad_vendida" placeholder="Cantidad a vender" value="{{ old('cantidad_vendida') }}" min="1" required oninput="calcularTotal()">
        
        <label for="precio_unitario">Precio Unitario:</label>
        <input type="number" id="precio_unitario" name="precio_unitario" placeholder="Precio por unidad" value="{{ old('precio_unitario') }}" step="0.01" class="readonly-field" readonly>
        
        <label for="total">Total de la Venta:</label>
        <input type="number" id="total" name="total" placeholder="0.00" step="0.01" class="readonly-field" readonly>

        <div class="button-container">
            <a href="{{ route('ventas.index') }}" class="form-button back-button">Atrás</a>
            <button type="submit" class="form-button save-button">Registrar Venta</button>
        </div>
    </form>
</body>
</html>