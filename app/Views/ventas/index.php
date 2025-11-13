<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Historial de Ventas<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Historial de Ventas</h1>
        <a href="<?= base_url('ventas/nueva') ?>" class="btn btn-success">
            + Nueva Venta
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="tabla-ventas" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Vendedor</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($ventas as $v): ?>
                        <tr>
                            <td><?= $v['id'] ?></td>
                            <td><?= $v['fecha'] ?></td> <td><?= esc($v['cliente'] ?? 'Público General') ?></td>
                            <td><?= esc($v['vendedor']) ?></td>
                            <td class="fw-bold">S/ <?= number_format($v['total'], 2) ?></td>
                            <td>
                                <button class="btn btn-info btn-sm text-white btnVerDetalle" data-id="<?= $v['id'] ?>">
                                    Ver Detalle
                                </button>
                                <a href="#" class="btn btn-danger btn-sm">
                                    <i class="bi bi-file-pdf"></i> PDF
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalDetalle" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Detalle de Venta #<span id="lblVentaID"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio Unit.</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-detalle-contenido">
                            </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#tabla-ventas').DataTable({
            "order": [[ 0, "desc" ]], // Ordenar por ID descendente (lo más nuevo primero)
            "language": {
                "processing": "Procesando...",
                "search": "Buscar:",
                "lengthMenu": "Mostrar _MENU_ registros",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ ventas",
                "infoEmpty": "Mostrando 0 a 0 de 0 ventas",
                "infoFiltered": "(filtrado de _MAX_ ventas totales)",
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

        // Ver Detalle
        $('.btnVerDetalle').click(function() {
            let ventaId = $(this).data('id');
            $('#lblVentaID').text(ventaId);
            let tbody = $('#tabla-detalle-contenido');
            tbody.html('<tr><td colspan="4" class="text-center">Cargando...</td></tr>');

            let modal = new bootstrap.Modal(document.getElementById('modalDetalle'));
            modal.show();

            // Petición AJAX
            $.ajax({
                url: "<?= base_url('ventas/detalle') ?>/" + ventaId,
                type: "GET",
                dataType: "json",
                success: function(res) {
                    tbody.empty();
                    if(res.success && res.data.length > 0) {
                        let totalCalculado = 0;
                        res.data.forEach(item => {
                            let subtotal = parseFloat(item.cantidad) * parseFloat(item.precio_unitario);
                            totalCalculado += subtotal;
                            tbody.append(`
                                <tr>
                                    <td>${item.nombre} <small class="text-muted">(${item.codigo})</small></td>
                                    <td>S/ ${parseFloat(item.precio_unitario).toFixed(2)}</td>
                                    <td>${item.cantidad}</td>
                                    <td class="fw-bold">S/ ${subtotal.toFixed(2)}</td>
                                </tr>
                            `);
                        });
                        // Fila de Total
                        tbody.append(`
                            <tr class="table-light">
                                <td colspan="3" class="text-end fw-bold">TOTAL:</td>
                                <td class="fw-bold">S/ ${totalCalculado.toFixed(2)}</td>
                            </tr>
                        `);
                    } else {
                        tbody.html('<tr><td colspan="4" class="text-center">No se encontraron detalles</td></tr>');
                    }
                },
                error: function() {
                    tbody.html('<tr><td colspan="4" class="text-center text-danger">Error al cargar</td></tr>');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>