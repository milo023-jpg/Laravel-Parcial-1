@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Pantalla para Venta de Productos
                    </h4>
                </div>
                <div class="card-body">
                    <form id="ventaForm">
                        @csrf
                        
                        <!-- Buscador de Productos y Ticket -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label for="producto_search" class="form-label fw-bold">Código del Producto:</label>
                                <div class="position-relative">
                                    <input type="text" 
                                           id="producto_search" 
                                           class="form-control form-control-lg" 
                                           placeholder="Buscar producto por código o nombre..."
                                           autocomplete="off">
                                    <div id="productos_dropdown" class="dropdown-menu w-100" style="display: none; max-height: 200px; overflow-y: auto;">
                                        <!-- Los productos se cargarán aquí dinámicamente -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="cantidad_ticket" class="form-label fw-bold">Ticket:</label>
                                <input type="number" 
                                       id="cantidad_ticket" 
                                       class="form-control form-control-lg text-center" 
                                       value="1" 
                                       min="1">
                            </div>
                        </div>

                        <!-- Cliente -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label for="cliente" class="form-label fw-bold">Cliente:</label>
                                <select id="cliente" class="form-select">
                                    <option value="">Cliente Mostrador</option>
                                    <!-- Los clientes se cargarán dinámicamente -->
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#clienteModal">
                                    <i class="fas fa-user-plus me-2"></i>Crear Cliente
                                </button>
                            </div>
                        </div>

                        <!-- Lista de Productos -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Descripción</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario</th>
                                        <th>Importe</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="productos_venta">
                                    <!-- Los productos seleccionados aparecerán aquí -->
                                    <tr id="no_productos" class="text-center text-muted">
                                        <td colspan="5">No hay productos agregados</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <button type="button" id="btn_cancelar" class="btn btn-danger btn-lg w-100">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" id="btn_vender" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-shopping-bag me-2"></i>Vender
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel Derecho - Totales -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white text-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>
                        Resumen de Venta
                    </h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="text-center mb-4">
                        <div class="display-6 fw-bold text-primary" id="logo_negocio">Mi Negocio</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Subtotal:</label>
                        </div>
                        <div class="col-6">
                            <div class="bg-light p-2 rounded text-end fw-bold" id="subtotal">$0.00</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Descuento:</label>
                        </div>
                        <div class="col-6">
                            <input type="number" id="descuento" class="form-control text-end" value="0" min="0" step="0.01">
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-6">
                            <label class="form-label fw-bold">Total a Pagar:</label>
                        </div>
                        <div class="col-6">
                            <div class="bg-primary text-white p-3 rounded text-end fw-bold h4 mb-0" id="valor_total">$0.00</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Pagó:</label>
                        </div>
                        <div class="col-6">
                            <input type="number" id="pago" class="form-control text-end bg-warning" step="0.01">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label fw-bold">Cambio:</label>
                        </div>
                        <div class="col-6">
                            <div class="bg-success text-white p-2 rounded text-end fw-bold" id="cambio">$0.00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Crear Cliente -->
<div class="modal fade" id="clienteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear Nuevo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="clienteForm">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tipo_documento" class="form-label">Tipo de Documento:</label>
                            <select id="tipo_documento" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="CC">Cédula de Ciudadanía</option>
                                <option value="TI">Tarjeta de Identidad</option>
                                <option value="CE">Cédula de Extranjería</option>
                                <option value="PP">Pasaporte</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="numero_documento" class="form-label">Número de Documento:</label>
                            <input type="text" id="numero_documento" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="nombre_cliente" class="form-label">Nombre Completo:</label>
                        <input type="text" id="nombre_cliente" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="direccion_cliente" class="form-label">Dirección:</label>
                        <input type="text" id="direccion_cliente" class="form-control" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="ciudad_cliente" class="form-label">Ciudad:</label>
                            <input type="text" id="ciudad_cliente" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telefono_cliente" class="form-label">Teléfono:</label>
                            <input type="text" id="telefono_cliente" class="form-control" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="guardar_cliente">Guardar Cliente</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let productosVenta = [];
    let productos = [];
    let clientes = [];
    
    // Cargar productos y clientes al iniciar
    cargarClientes();
    
    // Cargar clientes desde el backend
    function cargarClientes() {
        $.get('/ventas/clientes')
            .done(function(data) {
                clientes = data;
                const select = $('#cliente');
                select.empty();
                select.append('<option value="">Cliente Mostrador</option>');
                
                data.forEach(cliente => {
                    select.append(`<option value="${cliente.id}">${cliente.nombre}</option>`);
                });
            })
            .fail(function() {
                console.error('Error al cargar clientes');
            });
    }
    
    // Buscador de productos con AJAX
    function filtrarProductos(term) {
        return productos.filter(p => 
            p.codigo.toLowerCase().includes(term.toLowerCase()) ||
            p.nombre.toLowerCase().includes(term.toLowerCase())
        );
    }
    
    $('#producto_search').on('input', function() {
        const term = $(this).val();
        const dropdown = $('#productos_dropdown');
        
        if (term.length < 2) {
            dropdown.hide();
            return;
        }
        
        // Buscar productos via AJAX
        $.get('/pos/productos/buscar', { q: term })
            .done(function(data) {
                dropdown.empty();
                
                if (data.length > 0) {
                    data.forEach(producto => {
                        const item = $(`
                            <a class="dropdown-item producto-item" href="#" data-producto='${JSON.stringify(producto)}'>
                                <strong>${producto.codigo}</strong> - ${producto.nombre} 
                                <span class="text-muted">(${producto.precio} - Stock: ${producto.stock})</span>
                            </a>
                        `);
                        dropdown.append(item);
                    });
                    dropdown.show();
                } else {
                    dropdown.html('<div class="dropdown-item-text">No se encontraron productos</div>');
                    dropdown.show();
                }
            })
            .fail(function() {
                console.error('Error al buscar productos');
                dropdown.hide();
            });
    });
    
    // Manejar teclas en el buscador
    $('#producto_search').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const primerItem = $('#productos_dropdown .producto-item:first');
            if (primerItem.length) {
                primerItem.click();
            }
        }
    });
    
    // Seleccionar producto del dropdown
    $(document).on('click', '.producto-item', function(e) {
        e.preventDefault();
        const producto = JSON.parse($(this).data('producto'));
        const cantidad = parseInt($('#cantidad_ticket').val()) || 1;
        
        agregarProducto(producto, cantidad);
        $('#producto_search').val('').focus();
        $('#productos_dropdown').hide();
    });
    
    // Función para agregar producto
    function agregarProducto(producto, cantidad) {
        const existente = productosVenta.find(p => p.id === producto.id);
        
        if (existente) {
            existente.cantidad += cantidad;
        } else {
            productosVenta.push({
                id: producto.id,
                codigo: producto.codigo,
                nombre: producto.nombre,
                precio: producto.precio,
                cantidad: cantidad
            });
        }
        
        actualizarTablaProductos();
        calcularTotales();
    }
    
    // Actualizar tabla de productos
    function actualizarTablaProductos() {
        const tbody = $('#productos_venta');
        const noProductos = $('#no_productos');
        
        if (productosVenta.length === 0) {
            noProductos.show();
            return;
        }
        
        noProductos.hide();
        tbody.find('tr:not(#no_productos)').remove();
        
        productosVenta.forEach((producto, index) => {
            const importe = producto.precio * producto.cantidad;
            const row = $(`
                <tr>
                    <td>${producto.nombre}</td>
                    <td>
                        <input type="number" class="form-control cantidad-input" 
                               data-index="${index}" value="${producto.cantidad}" min="1" style="width: 80px;">
                    </td>
                    <td>$${producto.precio.toFixed(2)}</td>
                    <td>$${importe.toFixed(2)}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm eliminar-producto" data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
            tbody.append(row);
        });
    }
    
    // Actualizar cantidad
    $(document).on('change', '.cantidad-input', function() {
        const index = $(this).data('index');
        const nuevaCantidad = parseInt($(this).val()) || 1;
        productosVenta[index].cantidad = nuevaCantidad;
        actualizarTablaProductos();
        calcularTotales();
    });
    
    // Eliminar producto
    $(document).on('click', '.eliminar-producto', function() {
        const index = $(this).data('index');
        productosVenta.splice(index, 1);
        actualizarTablaProductos();
        calcularTotales();
    });
    
    // Calcular totales
    function calcularTotales() {
        const subtotal = productosVenta.reduce((total, producto) => 
            total + (producto.precio * producto.cantidad), 0);
        const descuento = parseFloat($('#descuento').val()) || 0;
        const total = subtotal - descuento;
        
        $('#subtotal').text('$' + subtotal.toFixed(2));
        $('#valor_total').text('$' + total.toFixed(2));
        
        calcularCambio();
    }
    
    // Calcular cambio
    function calcularCambio() {
        const total = parseFloat($('#valor_total').text().replace('$', '')) || 0;
        const pago = parseFloat($('#pago').val()) || 0;
        const cambio = pago - total;
        
        $('#cambio').text('$' + (cambio > 0 ? cambio.toFixed(2) : '0.00'));
        $('#cambio').removeClass('bg-success bg-danger')
                   .addClass(cambio >= 0 ? 'bg-success' : 'bg-danger');
    }
    
    // Events para recalcular
    $('#descuento, #pago').on('input', function() {
        calcularTotales();
    });
    
    // Cancelar venta
    $('#btn_cancelar').on('click', function() {
        if (confirm('¿Está seguro de que desea cancelar esta venta?')) {
            productosVenta = [];
            $('#cliente').val('');
            $('#descuento').val(0);
            $('#pago').val('');
            $('#producto_search').val('');
            actualizarTablaProductos();
            calcularTotales();
        }
    });
    
    // Procesar venta
    $('#ventaForm').on('submit', function(e) {
        e.preventDefault();
        
        if (productosVenta.length === 0) {
            alert('Debe agregar al menos un producto');
            return;
        }
        
        const total = parseFloat($('#valor_total').text().replace('
    
    // Guardar cliente
    $('#guardar_cliente').on('click', function() {
        const nombre = $('#nombre_cliente').val();
        const tipoDoc = $('#tipo_documento').val();
        const numeroDoc = $('#numero_documento').val();
        const direccion = $('#direccion_cliente').val();
        const ciudad = $('#ciudad_cliente').val();
        const telefono = $('#telefono_cliente').val();
        
        if (!nombre || !tipoDoc || !numeroDoc || !direccion || !ciudad || !telefono) {
            alert('Todos los campos son requeridos');
            return;
        }
        
        const clienteData = {
            _token: $('input[name="_token"]').val(),
            tipo_documento: tipoDoc,
            numero_documento: numeroDoc,
            nombre: nombre,
            direccion: direccion,
            ciudad: ciudad,
            telefono: telefono
        };
        
        // Deshabilitar botón
        const btn = $(this);
        btn.prop('disabled', true).text('Guardando...');
        
        $.ajax({
            url: '/pos/clientes',
            method: 'POST',
            data: clienteData,
            success: function(response) {
                // Agregar al select
                const cliente = response.cliente;
                const option = $(`<option value="${cliente.id}">${cliente.nombre} (${cliente.tipo_documento}: ${cliente.numero_documento})</option>`);
                $('#cliente').append(option);
                $('#cliente').val(cliente.id);
                
                // Limpiar y cerrar modal
                $('#clienteForm')[0].reset();
                $('#clienteModal').modal('hide');
                alert('Cliente creado exitosamente');
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    let mensaje = 'Errores:\n';
                    Object.values(errors).forEach(error => {
                        mensaje += '- ' + error.join('\n- ') + '\n';
                    });
                    alert(mensaje);
                } else {
                    alert('Error al crear el cliente');
                }
            },
            complete: function() {
                btn.prop('disabled', false).text('Guardar Cliente');
            }
        });
    });
    
    // Ocultar dropdown al hacer clic fuera
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#producto_search, #productos_dropdown').length) {
            $('#productos_dropdown').hide();
        }
    });
});
</script>
@endsection

@section('styles')
<style>
.dropdown-menu {
    position: absolute !important;
    z-index: 1000;
}

.producto-item:hover {
    background-color: #f8f9fa;
}

#logo_negocio {
    color: #0d6efd;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

.table th {
    border-top: none;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

#cantidad_ticket {
    font-weight: bold;
    font-size: 1.1rem;
}

.bg-warning {
    background-color: #fff3cd !important;
}
</style>
@endsection, ''));
        const pago = parseFloat($('#pago').val()) || 0;
        
        if (pago < total) {
            alert('El pago debe ser mayor o igual al total');
            return;
        }
        
        // Preparar datos para enviar
        const ventaData = {
            _token: $('input[name="_token"]').val(),
            cliente_id: $('#cliente').val() || null,
            productos: productosVenta.map(p => ({
                id: p.id,
                cantidad: p.cantidad
            })),
            descuento: parseFloat($('#descuento').val()) || 0,
            pago: pago
        };
        
        // Deshabilitar botón mientras procesa
        const btnVender = $('#btn_vender');
        const textoOriginal = btnVender.html();
        btnVender.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Procesando...');
        
        // Enviar venta al backend
        $.ajax({
            url: '/pos/venta',
            method: 'POST',
            data: ventaData,
            success: function(response) {
                alert('Venta procesada exitosamente');
                // Limpiar formulario
                $('#btn_cancelar').click();
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.message || 'Error al procesar la venta';
                alert(error);
            },
            complete: function() {
                btnVender.prop('disabled', false).html(textoOriginal);
            }
        });
    });
    
    // Guardar cliente
    $('#guardar_cliente').on('click', function() {
        const nombre = $('#nombre_cliente').val();
        if (!nombre) {
            alert('El nombre es requerido');
            return;
        }
        
        // Aquí guardarías el cliente en el backend
        const clienteData = {
            nombre: nombre,
            email: $('#email_cliente').val(),
            telefono: $('#telefono_cliente').val()
        };
        
        console.log('Nuevo cliente:', clienteData);
        
        // Agregar al select (simulado)
        const option = $(`<option value="cliente_${Date.now()}">${nombre}</option>`);
        $('#cliente').append(option);
        $('#cliente').val(option.val());
        
        // Limpiar y cerrar modal
        $('#clienteForm')[0].reset();
        $('#clienteModal').modal('hide');
    });
    
    // Ocultar dropdown al hacer clic fuera
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#producto_search, #productos_dropdown').length) {
            $('#productos_dropdown').hide();
        }
    });
});
</script>
@endsection

@section('styles')
<style>
.dropdown-menu {
    position: absolute !important;
    z-index: 1000;
}

.producto-item:hover {
    background-color: #f8f9fa;
}

#logo_negocio {
    color: #0d6efd;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

.table th {
    border-top: none;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

#cantidad_ticket {
    font-weight: bold;
    font-size: 1.1rem;
}

.bg-warning {
    background-color: #fff3cd !important;
}
</style>
@endsection