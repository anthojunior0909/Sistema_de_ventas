<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Mi Perfil<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <h1 class="h3 mb-4">Mi Perfil</h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <i class="bi bi-key"></i> Cambiar Contraseña
                </div>
                <div class="card-body">

                    <?php if(session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>

                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <?php if(session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul>
                            <?php foreach(session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('perfil/password') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label">Usuario:</label>
                            <input type="text" class="form-control" value="<?= session()->get('username') ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña Actual:</label>
                            <input type="password" name="password_actual" class="form-control" required>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña:</label>
                            <input type="password" name="password_nueva" class="form-control" required>
                            <div class="form-text">Mínimo 6 caracteres.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmar Nueva Contraseña:</label>
                            <input type="password" name="password_confirm" class="form-control" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning">Actualizar Contraseña</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>