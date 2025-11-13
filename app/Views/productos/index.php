<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
    Gestión de Productos
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <h1 class="h3 mb-3">Gestión de Productos</h1>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="card-title">Lista de Productos</h5>
            <button id="btnAnadirProducto" class="btn btn-primary btn-sm float-end">
                Añadir Nuevo Producto
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla-productos" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Precio Venta</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $key => $producto): ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td><?= esc($producto['codigo']) ?></td>
                                <td><?= esc($producto['nombre']) ?></td>
                                <td>S/ <?= esc($producto['precio_venta']) ?></td>
                                <td><?= esc($producto['stock']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm btnEditar" data-id="<?= $producto['id'] ?>">
                                        Editar
                                    </button>
                                    <button class="btn btn-danger btn-sm btnEliminar" data-id="<?= $producto['id'] ?>">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalProducto" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Añadir Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formProducto">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="producto_id">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="codigo" class="form-label">Código</label>
                            <input type="text" class="form-control" id="codigo" name="codigo">
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="precio_compra" class="form-label">Precio Compra</label>
                                <input type="number" step="0.01" class="form-control" id="precio_compra" name="precio_compra">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="precio_venta" class="form-label">Precio Venta *</label>
                                <input type="number" step="0.01" class="form-control" id="precio_venta" name="precio_venta" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock Inicial *</label>
                            <input type="number" class="form-control" id="stock" name="stock" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        $(document).ready(function() {
            
            // 1. Inicializar DataTable (Con Botones)
            var tablaProductos = $('#tabla-productos').DataTable({
                "order": [[ 0, "desc" ]],
                "dom": 'Bfrtip',
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                        className: 'btn btn-success btn-sm'
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="bi bi-filetype-csv"></i> CSV',
                        className: 'btn btn-primary btn-sm'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm'
                    },
                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer"></i> Imprimir',
                        className: 'btn btn-secondary btn-sm'
                    }
                ],
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

            // BOTÓN AÑADIR
            $('#btnAnadirProducto').on('click', function() {
                $('#formProducto')[0].reset();
                $('#modalLabel').text('Añadir Producto');
                $('#producto_id').val('');
                var modal = new bootstrap.Modal(document.getElementById('modalProducto'));
                modal.show();
            });

            // BOTÓN EDITAR
            $('#tabla-productos').on('click', '.btnEditar', function() {
                var productoID = $(this).data('id');
                $.ajax({
                    url: "<?= base_url('productos') ?>/" + productoID + "/edit",
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $('#producto_id').val(response.data.id);
                            $('#codigo').val(response.data.codigo);
                            $('#nombre').val(response.data.nombre);
                            $('#precio_compra').val(response.data.precio_compra);
                            $('#precio_venta').val(response.data.precio_venta);
                            $('#stock').val(response.data.stock);
                            
                            $('#modalLabel').text('Editar Producto');
                            var modal = new bootstrap.Modal(document.getElementById('modalProducto'));
                            modal.show();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });

            // FORMULARIO
            $('#formProducto').on('submit', function(e) {
                e.preventDefault();
                var productoID = $('#producto_id').val();
                var url = productoID ? "<?= base_url('productos') ?>/" + productoID : "<?= base_url('productos') ?>";
                var method = "POST";
                var formData = $(this).serialize();

                if (productoID) {
                    formData += "&_method=PUT";
                }

                $.ajax({
                    type: method,
                    url: url,
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            location.reload();
                        } else {
                            var msg = response.message ? response.message : 'Error de validación';
                            if(response.errors) {
                                msg += '\n' + Object.values(response.errors).join('\n');
                            }
                            alert(msg);
                        }
                    },
                    error: function() {
                        alert('Error al conectar con el servidor.');
                    }
                });
            });

            // ELIMINAR
            $('#tabla-productos').on('click', '.btnEliminar', function() {
                var productoID = $(this).data('id');
                if (confirm('¿Estás seguro de eliminar este producto?')) {
                    $.ajax({
                        url: "<?= base_url('productos') ?>/" + productoID,
                        type: "POST",
                        data: { _method: "DELETE", "<?= csrf_token() ?>": "<?= csrf_hash() ?>" },
                        dataType: "json",
                        success: function(response) {
                            if(response.success) { location.reload(); } 
                            else { alert(response.message); }
                        }
                    });
                }
            });
        });
    </script>
<?= $this->endSection() ?>