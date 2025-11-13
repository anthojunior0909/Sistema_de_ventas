<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Gestión de Clientes<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <h1 class="h3 mb-3">Gestión de Clientes</h1>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="card-title">Listado</h5>
            <button id="btnAnadirCliente" class="btn btn-primary btn-sm float-end">Nuevo Cliente</button>
        </div>
        <div class="card-body">
            <table id="tabla-clientes" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Documento</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($clientes as $k => $c): ?>
                        <tr>
                            <td><?= $k + 1 ?></td>
                            <td><?= esc($c['nombre']) ?></td>
                            <td><?= esc($c['documento']) ?></td>
                            <td><?= esc($c['telefono']) ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm btnEditar" data-id="<?= $c['id'] ?>">Editar</button>
                                <button class="btn btn-danger btn-sm btnEliminar" data-id="<?= $c['id'] ?>">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalCliente" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Datos del Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formCliente">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="cliente_id">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo *</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Documento (DNI/RUC)</label>
                            <input type="text" class="form-control" name="documento" id="documento">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control" name="telefono" id="telefono">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#tabla-clientes').DataTable();

        // ABRIR MODAL
        $('#btnAnadirCliente').click(function() {
            $('#formCliente')[0].reset();
            $('#cliente_id').val('');
            $('#modalLabel').text('Nuevo Cliente');
            new bootstrap.Modal('#modalCliente').show();
        });

        // EDITAR
        $('.btnEditar').click(function() {
            var id = $(this).data('id');
            $.get("<?= base_url('clientes') ?>/" + id + "/edit", function(res) {
                if(res.success) {
                    $('#cliente_id').val(res.data.id);
                    $('#nombre').val(res.data.nombre);
                    $('#documento').val(res.data.documento);
                    $('#telefono').val(res.data.telefono);
                    $('#modalLabel').text('Editar Cliente');
                    new bootstrap.Modal('#modalCliente').show();
                }
            });
        });

        // GUARDAR
        $('#formCliente').submit(function(e) {
            e.preventDefault();
            var id = $('#cliente_id').val();
            var url = id ? "<?= base_url('clientes') ?>/" + id : "<?= base_url('clientes') ?>";
            var method = "POST"; // CodeIgniter maneja PUT con _method
            var data = $(this).serialize();
            
            if(id) data += "&_method=PUT";

            $.ajax({
                url: url, type: method, data: data, dataType: 'json',
                success: function(res) {
                    if(res.success) {
                        location.reload();
                    } else {
                        alert(res.message);
                    }
                }
            });
        });

        // ELIMINAR
        $('.btnEliminar').click(function() {
            var id = $(this).data('id');
            if(confirm('¿Eliminar cliente?')) {
                $.post("<?= base_url('clientes') ?>/" + id, {
                    _method: 'DELETE',
                    "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
                }, function(res) {
                    if(res.success) location.reload();
                });
            }
        });
    });
</script>
<?= $this->endSection() ?>