<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Gestión de Caja<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <h1 class="h3 mb-4">Gestión de Caja</h1>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    Estado Actual
                </div>
                <div class="card-body text-center">
                    
                    <?php if($cajaAbierta): ?>
                        <div class="alert alert-success">
                            <h4 class="alert-heading">¡Caja Abierta!</h4>
                            <p>Iniciaste con: <strong>S/ <?= number_format($cajaAbierta['monto_inicial'], 2) ?></strong></p>
                            <p>Fecha: <?= $cajaAbierta['fecha_apertura'] ?></p>
                        </div>
                        
                        <hr>
                        <h5>Cerrar Turno</h5>
                        <form action="<?= base_url('caja/cerrar') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label>Dinero total en efectivo (Contado):</label>
                                <input type="number" step="0.01" name="monto_final" class="form-control" required>
                            </div>
                            <button class="btn btn-danger w-100">Cerrar Caja</button>
                        </form>

                    <?php else: ?>
                        <div class="alert alert-warning">
                            <h4 class="alert-heading">Caja Cerrada</h4>
                            <p>Debes abrir caja para poder realizar ventas.</p>
                        </div>

                        <hr>
                        <h5>Apertura de Caja</h5>
                        <form action="<?= base_url('caja/abrir') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label>Monto Inicial (Sencillo/Base):</label>
                                <input type="number" step="0.01" name="monto_inicial" class="form-control" value="0.00" required>
                            </div>
                            <button class="btn btn-primary w-100">Abrir Caja</button>
                        </form>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">Últimas Sesiones</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Apertura</th>
                                <th>Inicial</th>
                                <th>Final</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($historial as $h): ?>
                                <tr>
                                    <td>
                                        <span class="badge <?= $h['estado'] == 'abierta' ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= ucfirst($h['estado']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m H:i', strtotime($h['fecha_apertura'])) ?></td>
                                    <td>S/ <?= $h['monto_inicial'] ?></td>
                                    <td><?= $h['monto_final'] ? 'S/ '.$h['monto_final'] : '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>