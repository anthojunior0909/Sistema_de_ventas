<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Gestión de Usuarios<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <h1 class="h3 mb-3">Gestión de Usuarios</h1>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="card-title">Usuarios del Sistema</h5>
            <button id="btnCrear" class="btn btn-primary btn-sm float-end">Nuevo Usuario</button>
        </div>
        <div class="card-body">
            <table id="tabla-usuarios" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($usuarios as $u): ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td><?= esc($u['username']) ?></td>
                            <td>
                                <span class="badge <?= $u['role'] == 'admin' ? 'bg-danger' : 'bg-primary' ?>">
                                    <?= ucfirst($u['role']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if(session()->get('user_id') != $u['id']): ?>
                                    <button class="btn btn-danger btn-sm btnEliminar" data-id="<?= $u['id'] ?>">
                                        Eliminar
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted small">(Tú)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formUsuario">
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">Nombre de Usuario:</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña Inicial:</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol:</label>
                            <select name="role" class="form-select">
                                <option value="vendedor">Vendedor</option>
                                <option value="admin">Administrador</option>
                            </select>
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
        $('#tabla-usuarios').DataTable();

        $('#btnCrear').click(function() {
            $('#formUsuario')[0].reset();
            new bootstrap.Modal('#modalUsuario').show();
        });

        $('#formUsuario').submit(function(e) {
            e.preventDefault();
            $.post("<?= base_url('usuarios/guardar') ?>", $(this).serialize(), function(res) {
                if(res.success) { location.reload(); }
                else { alert(res.message); }
            }, 'json');
        });

        $('.btnEliminar').click(function() {
            let id = $(this).data('id');
            if(confirm('¿Eliminar este usuario permanentemente?')) {
                $.post("<?= base_url('usuarios/eliminar') ?>/" + id, {
                    "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
                }, function(res) {
                    if(res.success) { location.reload(); }
                    else { alert(res.message); }
                }, 'json');
            }
        });
    });
</script>
<?= $this->endSection() ?>