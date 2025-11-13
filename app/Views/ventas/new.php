<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Nueva Venta<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-7">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">1. Seleccionar Productos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabla-busqueda" class="table table-hover table-sm" style="width:100%">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($productos as $p): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($p['codigo']) ?></strong><br>
                                        <?= esc($p['nombre']) ?>
                                    </td>
                                    <td>S/ <?= esc($p['precio_venta']) ?></td>
                                    <td>
                                        <span class="badge <?= $p['stock'] > 5 ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $p['stock'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($p['stock'] > 0): ?>
                                            <button class="btn btn-sm btn-outline-primary btn-agregar"
                                                data-id="<?= $p['id'] ?>"
                                                data-nombre="<?= esc($p['nombre']) ?>"
                                                data-precio="<?= $p['precio_venta'] ?>"
                                                data-stock="<?= $p['stock'] ?>">
                                                +
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-secondary" disabled>Sin Stock</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">2. Detalle de Venta</h5>
            </div>
            <div class="card-body d-flex flex-column">
                
                <div class="mb-3">
                    <label class="form-label">Cliente:</label>
                    <select id="cliente_id" class="form-select">
                        <?php foreach($clientes as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= esc($c['nombre']) ?> (<?= esc($c['documento']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="table-responsive flex-grow-1" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-striped table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Prod.</th>
                                <th width="70">Cant.</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tabla-carrito">
                            <tr>
                                <td colspan="4" class="text-center text-muted">Carrito vacío</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 border-top pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Total:</h4>
                        <h3 class="mb-0 text-success fw-bold">S/ <span id="total-venta">0.00</span></h3>
                    </div>
                    
                    <div class="d-grid">
                        <button id="btn-finalizar" class="btn btn-success btn-lg" disabled>
                            Confirmar Venta
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Variable global para guardar los productos seleccionados
    let carrito = [];

    $(document).ready(function() {
        // 1. Convertir la tabla de productos en DataTable (para buscar rápido)
        $('#tabla-busqueda').DataTable({
            "pageLength": 5,
            "lengthChange": false,
            "language": {
                "processing": "Procesando...",
                "search": "Buscar:",
                "lengthMenu": "Mostrar _MENU_ registros",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ productos",
                "infoEmpty": "Mostrando 0 a 0 de 0 productos",
                "infoFiltered": "(filtrado de _MAX_ productos totales)",
                "loadingRecords": "Cargando...",
                "zeroRecords": "No se encontraron resultados",
                "emptyTable": "Ningún dato disponible en esta tabla",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });

        // 2. Evento: Clic en botón "+" de un producto
        $('.table').on('click', '.btn-agregar', function() {
            let id = $(this).data('id');
            let nombre = $(this).data('nombre');
            let precio = parseFloat($(this).data('precio'));
            let stockMax = parseInt($(this).data('stock'));

            // Verificamos si ya está en el carrito
            let existe = carrito.find(item => item.id === id);

            if (existe) {
                if (existe.cantidad < stockMax) {
                    existe.cantidad++;
                } else {
                    alert("¡No hay más stock disponible!");
                    return;
                }
            } else {
                carrito.push({
                    id: id,
                    nombre: nombre,
                    precio: precio,
                    cantidad: 1,
                    stockMax: stockMax
                });
            }

            actualizarCarritoUI();
        });

        // 3. Evento: Eliminar producto del carrito
        $('#tabla-carrito').on('click', '.btn-quitar', function() {
            let id = $(this).data('id');
            carrito = carrito.filter(item => item.id !== id); // Lo sacamos del array
            actualizarCarritoUI();
        });

        // 4. Función para dibujar el carrito y calcular totales
        function actualizarCarritoUI() {
            let tbody = $('#tabla-carrito');
            tbody.empty(); // Limpiamos la tabla visual
            let totalGeneral = 0;

            if (carrito.length === 0) {
                tbody.html('<tr><td colspan="4" class="text-center text-muted">Carrito vacío</td></tr>');
                $('#btn-finalizar').prop('disabled', true);
                $('#total-venta').text('0.00');
                return;
            }

            carrito.forEach(item => {
                let subtotal = item.cantidad * item.precio;
                totalGeneral += subtotal;

                tbody.append(`
                    <tr>
                        <td>${item.nombre}</td>
                        <td>${item.cantidad}</td>
                        <td>S/ ${subtotal.toFixed(2)}</td>
                        <td>
                            <button class="btn btn-danger btn-sm btn-quitar p-0 px-1" data-id="${item.id}">&times;</button>
                        </td>
                    </tr>
                `);
            });

            $('#total-venta').text(totalGeneral.toFixed(2));
            $('#btn-finalizar').prop('disabled', false);
        }

        // 5. Evento: Finalizar Venta (Enviar al servidor)
        $('#btn-finalizar').click(function() {
            if (!confirm("¿Confirmar venta?")) return;

            // Preparamos los datos para enviar
            let datosVenta = {
                cliente_id: $('#cliente_id').val(),
                total: parseFloat($('#total-venta').text()),
                productos: carrito
            };

            // AJAX
            $.ajax({
                url: "<?= base_url('ventas/guardar') ?>",
                type: "POST",
                data: JSON.stringify(datosVenta), // Enviamos como JSON
                contentType: "application/json",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    // Importante: El token CSRF en el header para JSON
                    "X-CSRF-TOKEN": "<?= csrf_hash() ?>" 
                },
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        alert(res.message);
                        carrito = []; // Vaciar carrito
                        actualizarCarritoUI();
                        location.reload(); // Recargar para actualizar stocks en la lista
                    } else {
                        alert(res.message);
                    }
                },
                error: function(err) {
                    alert("Error al procesar la venta");
                    console.error(err);
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>