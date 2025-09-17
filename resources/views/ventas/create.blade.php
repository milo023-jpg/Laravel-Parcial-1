@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 1200px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Nueva Venta</h1>
        <a href="{{ route('pos') }}" class="btn btn-secondary">Cancelar</a>
    </div>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pos.venta.store') }}" method="POST" id="formVenta">
        @csrf
        
        <div class="row">
            {{-- Sección Cliente --}}
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Información del Cliente</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="cliente_id" class="form-label">Cliente (Opcional)</label>
                            <div class="d-flex gap-2">
                                <select name="cliente_id" id="cliente_id" class="form-select">
                                    <option value="">Cliente Mostrador</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" 
                                            {{ (old('cliente_id') == $cliente->id || session('nuevo_cliente_id') == $cliente->id) ? 'selected' : '' }}>
                                            {{ $cliente->nombre }} - {{ $cliente->numero_documento }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoCliente">
                                    Nuevo Cliente
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sección Descuento y Pago --}}
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Descuento y Pago</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="descuento" class="form-label">Descuento ($)</label>
                                    <input type="number" 
                                           name="descuento" 
                                           id="descuento" 
                                           class="form-control" 
                                           value="{{ old('descuento', 0) }}"
                                           min="0" 
                                           step="100"
                                           onchange="calcularTotales()">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pago" class="form-label">Pago Recibido ($)*</label>
                                    <input type="number" 
                                           name="pago" 
                                           id="pago" 
                                           class="form-control" 
                                           value="{{ old('pago') }}"
                                           required 
                                           min="0" 
                                           step="100"
                                           onchange="calcularTotales()">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sección Productos --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Productos de la Venta</h5>
            </div>
            <div class="card-body">
                <div id="productos-container">
                    {{-- Primer producto --}}
                    <div class="row mb-3 producto-row">
                        <div class="col-md-4">
                            <label class="form-label">Producto *</label>
                            <select name="productos[0]" class="form-select producto-select" required onchange="calcularTotales()">
                                <option value="">Seleccione un producto...</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}" 
                                            data-precio="{{ $producto->precio }}"
                                            {{ old('productos.0') == $producto->id ? 'selected' : '' }}>
                                        {{ $producto->nombre }} - ${{ number_format($producto->precio, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cantidad *</label>
                            <input type="number" name="cantidades[0]" 
                                class="form-control cantidad-input" 
                                min="1" value="{{ old('cantidades.0', 1) }}" 
                                required onchange="calcularTotales()">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Subtotal</label>
                            <input type="text" class="form-control subtotal-display" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="button" class="btn btn-danger btn-sm eliminar-producto" onclick="eliminarProducto(this)">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="button" class="btn btn-outline-primary" onclick="agregarProducto()">
                    Agregar Producto
                </button>
            </div>
        </div>

        {{-- Resumen de Totales --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Resumen de la Venta</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Subtotal: $<span id="subtotal">0</span></strong>
                    </div>
                    <div class="col-md-3">
                        <strong>Descuento: $<span id="descuento-display">0</span></strong>
                    </div>
                    <div class="col-md-3">
                        <strong>Total: $<span id="total">0</span></strong>
                    </div>
                    <div class="col-md-3">
                        <strong>Cambio: $<span id="cambio">0</span></strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">Registrar Venta</button>
            <a href="{{ route('pos') }}" class="btn btn-secondary">Cancelar</a>
        </div>

    </form>

</div>

{{-- Modal Nuevo Cliente --}}
<div class="modal fade" id="modalNuevoCliente" tabindex="-1" aria-labelledby="modalNuevoClienteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevoClienteLabel">Registrar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pos.cliente.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal_nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_nombre" name="nombre" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_tipo_documento" class="form-label">Tipo de Documento <span class="text-danger">*</span></label>
                        <select class="form-select" id="modal_tipo_documento" name="tipo_documento" required>
                            <option value="">Seleccione...</option>
                            <option value="CC">CC</option>
                            <option value="TI">TI</option>
                            <option value="CE">CE</option>
                            <option value="NIT">NIT</option>
                            <option value="PASAPORTE">PASAPORTE</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_numero_documento" class="form-label">Número de Documento <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_numero_documento" name="numero_documento" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="modal_telefono" name="telefono">
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_direccion" class="form-label">Dirección</label>
                        <textarea class="form-control" id="modal_direccion" name="direccion" rows="2"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_ciudad" class="form-label">Ciudad</label>
                        <input type="text" class="form-control" id="modal_ciudad" name="ciudad">
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="modal_frecuente" name="frecuente" value="1">
                        <label class="form-check-label" for="modal_frecuente">
                            Cliente frecuente
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let contadorProductos = 0;

// Calcular totales cuando la página carga
document.addEventListener('DOMContentLoaded', function() {
    calcularTotales();
});

function agregarProducto() {
    contadorProductos++;
    
    const container = document.getElementById('productos-container');
    const productoDiv = document.createElement('div');
    productoDiv.className = 'row mb-3 producto-row';
    
    productoDiv.innerHTML = `
        <div class="col-md-4">
            <label class="form-label">Producto *</label>
            <select name="productos[${contadorProductos}]" class="form-select producto-select" onchange="calcularTotales()">
                <option value="">Seleccione un producto...</option>
                @foreach($productos as $producto)
                    <option value="{{ $producto->id }}" data-precio="{{ $producto->precio }}">
                        {{ $producto->nombre }} - ${{ number_format($producto->precio, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Cantidad *</label>
            <input type="number" name="cantidades[${contadorProductos}]" 
                class="form-control cantidad-input" 
                min="1" value="1" onchange="calcularTotales()">
        </div>
        <div class="col-md-3">
            <label class="form-label">Subtotal</label>
            <input type="text" class="form-control subtotal-display" readonly>
        </div>
        <div class="col-md-2">
            <label class="form-label">&nbsp;</label>
            <div>
                <button type="button" class="btn btn-danger btn-sm eliminar-producto" onclick="eliminarProducto(this)">
                    Eliminar
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(productoDiv);
    calcularTotales();
}

function eliminarProducto(button) {
    const productosRows = document.querySelectorAll('.producto-row');
    if (productosRows.length > 1) {
        button.closest('.producto-row').remove();
        calcularTotales();
    } else {
        alert('Debe haber al menos un producto en la venta');
    }
}

function calcularTotales() {
    let subtotal = 0;
    
    document.querySelectorAll('.producto-row').forEach(row => {
        const selectProducto = row.querySelector('.producto-select');
        const inputCantidad = row.querySelector('.cantidad-input');
        const displaySubtotal = row.querySelector('.subtotal-display');
        
        if (selectProducto && inputCantidad) {
            const opcionSeleccionada = selectProducto.options[selectProducto.selectedIndex];
            const precio = parseFloat(opcionSeleccionada.getAttribute('data-precio')) || 0;
            const cantidad = parseInt(inputCantidad.value) || 0;
            
            if (selectProducto.value && cantidad > 0) {
                const subtotalProducto = precio * cantidad;
                displaySubtotal.value = '$' + subtotalProducto.toLocaleString();
                subtotal += subtotalProducto;
            } else {
                displaySubtotal.value = '';
            }
        }
    });
    
    const descuento = parseFloat(document.getElementById('descuento').value) || 0;
    const total = subtotal - descuento;
    const pago = parseFloat(document.getElementById('pago').value) || 0;
    const cambio = Math.max(0, pago - total);
    
    document.getElementById('subtotal').textContent = subtotal.toLocaleString();
    document.getElementById('descuento-display').textContent = descuento.toLocaleString();
    document.getElementById('total').textContent = total.toLocaleString();
    document.getElementById('cambio').textContent = cambio.toLocaleString();
    
    // Cambiar color del cambio
    const cambioSpan = document.getElementById('cambio');
    if (pago < total && pago > 0) {
        cambioSpan.style.color = 'red';
    } else {
        cambioSpan.style.color = 'green';
    }
}
</script>

@endsection