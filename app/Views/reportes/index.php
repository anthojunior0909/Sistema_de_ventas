<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Reportes<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <h1 class="h3 mb-4">Generar Reportes</h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    Reporte de Ventas (Excel)
                </div>
                <div class="card-body">
                    
                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('reportes/excel') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Fecha Inicio:</label>
                            <input type="date" name="fecha_inicio" class="form-control" value="<?= date('Y-m-01') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fecha Fin:</label>
                            <input type="date" name="fecha_fin" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-file-excel"></i> Descargar Excel
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>