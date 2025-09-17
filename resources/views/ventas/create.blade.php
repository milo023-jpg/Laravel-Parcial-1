@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 1200px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Nueva Venta</h1>
    </div>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
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

    <form action="/pos/venta" method="POST" id="formVenta">
        @csrf
        
        <div class="row">
            {{-- Sección Cliente --}}
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">👤 Información del Cliente</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="cliente_id" class="form-label">Cliente (Opcional)</label>
                            <div class="d-flex gap-2">
                                <select name="cliente_id" id="cliente_id" class="form-select">
                                    <option value="">Cliente Mostrador</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
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
                        <h5 class="card-title mb-0">💰 Descuento y Pago</h5>
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
                                           step="100">
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
                                           step="100">
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
                <h5 class="card-title mb-0">🛒 Productos de la Venta</h5>
            </div>
            <div class="card-body">
                <div id="productos-container">
                    {{-- Productos se agregan aquí dinámicamente --}}
                </div>
                
                <button type="button" class="btn btn-outline-primary" id="agregar-producto">
                    ➕ Agregar Producto
                </button>
            </div>
        </div>

        {{-- Resumen de Totales --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">📊 Resumen de la Venta</h5>
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
            <button type="button" id="btnRegistrar" class="btn btn-success">💾 Registrar Venta</button>
            <button type="button" id="btnCancelar" class="btn btn-secondary">❌ Cancelar</button>
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
            <form id="formNuevoCliente">
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

{{-- jQuery + jQuery UI para autocomplete --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let contadorProductos = 0;
        const productos = @json($productos);
        
        // Agregar primer producto automáticamente
        agregarProducto();
        
        document.getElementById('agregar-producto').addEventListener('click', agregarProducto);
        
        function agregarProducto() {
        contadorProductos++;
        
        const container = document.getElementById('productos-container');
        const productoDiv = document.createElement('div');
        productoDiv.className = 'row mb-3 producto-row';
        productoDiv.id = `producto-${contadorProductos}`;
        
        productoDiv.innerHTML = `
            <div class="col-md-4">
                <label class="form-label">Producto *</label>
                <input type="text" 
                    id="buscar-producto-${contadorProductos}" 
                    class="form-control buscar-producto" 
                    placeholder="Buscar producto..." required>
                <input type="hidden" 
                    name="productos[${contadorProductos}][id]" 
                    id="producto-id-${contadorProductos}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Cantidad *</label>
                <input type="number" name="productos[${contadorProductos}][cantidad]" 
                    class="form-control cantidad-input" min="1" value="1" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Subtotal</label>
                <input type="text" class="form-control subtotal-display" readonly>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="button" class="btn btn-danger btn-sm eliminar-producto">🗑️</button>
                </div>
            </div>
        `;
        
        container.appendChild(productoDiv);
        
        // Eventos
        const cantidadInput = productoDiv.querySelector('.cantidad-input');
        const eliminarBtn = productoDiv.querySelector('.eliminar-producto');

        cantidadInput.addEventListener('input', calcularTotales);
        eliminarBtn.addEventListener('click', function() {
            if (document.querySelectorAll('.producto-row').length > 1) {
                productoDiv.remove();
                calcularTotales();
            } else {
                alert('Debe haber al menos un producto en la venta');
            }
        });

        // Autocomplete con jQuery UI
        $(`#buscar-producto-${contadorProductos}`).autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "/productos/buscar", // tu ruta en web.php
                    data: { term: request.term },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.nombre + " - $" + item.precio,
                                value: item.nombre,
                                id: item.id,
                                precio: item.precio
                            };
                        }));
                    }
                });
            },
            select: function(event, ui) {
                // Guardar el ID en el hidden input
                $(`#producto-id-${contadorProductos}`).val(ui.item.id);
                $(`#producto-id-${contadorProductos}`).attr('data-precio', ui.item.precio);

                // Actualizar subtotal inicial
                const cantidad = cantidadInput.value || 1;
                const subtotalInput = productoDiv.querySelector('.subtotal-display');
                subtotalInput.value = (ui.item.precio * cantidad).toLocaleString();

                calcularTotales();
            }
        });
    }

    
    // Eventos para descuento y pago
    document.getElementById('descuento').addEventListener('input', calcularTotales);
    document.getElementById('pago').addEventListener('input', calcularTotales);
    
    function calcularTotales() {
        let subtotal = 0;
        
        document.querySelectorAll('.producto-row').forEach(row => {
            const hiddenId = row.querySelector('input[type="hidden"]');
            const cantidadInput = row.querySelector('.cantidad-input');
            const subtotalDisplay = row.querySelector('.subtotal-display');

            const precio = parseFloat(hiddenId.dataset.precio || 0);
            const cantidad = parseInt(cantidadInput.value) || 0;

            if (hiddenId.value && cantidad > 0) {
                const subtotalProducto = precio * cantidad;
                subtotalDisplay.value = '$' + subtotalProducto.toLocaleString();
                subtotal += subtotalProducto;
            } else {
                subtotalDisplay.value = '';
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


    // Manejo del formulario de nuevo cliente
    document.getElementById('formNuevoCliente').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('/pos/clientes', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cerrar modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevoCliente'));
                modal.hide();
                
                // Limpiar formulario
                document.getElementById('formNuevoCliente').reset();
                
                // Agregar cliente al select
                const clienteSelect = document.getElementById('cliente_id');
                const option = document.createElement('option');
                option.value = data.cliente.id;
                option.textContent = data.cliente.nombre + ' - ' + data.cliente.numero_documento;
                option.selected = true;
                clienteSelect.appendChild(option);
                
                // Mostrar mensaje de éxito
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.innerHTML = `
                    ${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.container').insertBefore(alertDiv, document.querySelector('form'));
            } else {
                alert('Error al crear el cliente: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al crear el cliente');
        });
    });

    // Registrar venta con AJAX (moved inside DOMContentLoaded)
    document.getElementById('btnRegistrar').addEventListener('click', function() {
        const form = document.getElementById('formVenta');
        
        // Construir datos manualmente para que coincidan con la validación
        const formData = new FormData();
        
        // Agregar CSRF token
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        // Agregar cliente_id
        const clienteId = document.getElementById('cliente_id').value;
        if (clienteId) {
            formData.append('cliente_id', clienteId);
        }
        
        // Agregar descuento y pago
        formData.append('descuento', document.getElementById('descuento').value || 0);
        formData.append('pago', document.getElementById('pago').value);
        
        // Validar que haya productos válidos
        const productosRows = document.querySelectorAll('.producto-row');
        let hayProductosValidos = false;
        
        productosRows.forEach((row, index) => {
            const productoId = row.querySelector('input[type="hidden"]').value;
            const cantidad = row.querySelector('.cantidad-input').value;
            
            if (productoId && cantidad) {
                formData.append(`productos[${index}][id]`, productoId);
                formData.append(`productos[${index}][cantidad]`, cantidad);
                hayProductosValidos = true;
            }
        });

        if (!hayProductosValidos) {
            alert('Debe seleccionar al menos un producto válido');
            return;
        }

        fetch("/pos/venta", {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error('Server error: ' + response.status + ' - ' + text);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Limpiar formulario
                form.reset();

                // Limpiar productos dinámicos y volver a crear uno vacío
                document.getElementById('productos-container').innerHTML = '';
                contadorProductos = 0;
                agregarProducto();

                // Reset totales
                document.getElementById('subtotal').textContent = '0';
                document.getElementById('descuento-display').textContent = '0';
                document.getElementById('total').textContent = '0';
                document.getElementById('cambio').textContent = '0';

                // Mostrar alerta de éxito
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.innerHTML = `
                    ${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.container').insertBefore(alertDiv, form);
            } else {
                alert('Error al registrar la venta: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Full error:', error);
            alert('Error completo: ' + error.message);
        });
    });

    // 🔥 Cancelar venta (moved inside DOMContentLoaded)
    document.getElementById('btnCancelar').addEventListener('click', function() {
        const form = document.getElementById('formVenta');
        form.reset();

        // Limpiar productos dinámicos y volver a crear uno vacío
        document.getElementById('productos-container').innerHTML = '';
        contadorProductos = 0;
        agregarProducto();

        // Reset totales
        document.getElementById('subtotal').textContent = '0';
        document.getElementById('descuento-display').textContent = '0';
        document.getElementById('total').textContent = '0';
        document.getElementById('cambio').textContent = '0';
    });

});
</script>
@endsection
