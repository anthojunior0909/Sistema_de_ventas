<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Resumen del Negocio</h1>
        <span class="text-muted">Hoy: <?= date('d/m/Y') ?></span>
    </div>

    <div class="row">
        
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 border-success border-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Ventas de Hoy</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                S/ <?= number_format($ventasHoy, 2) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <h2 class="text-success">💰</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 border-primary border-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Productos Registrados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $totalProductos ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <h2 class="text-primary">📦</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 border-info border-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Clientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $totalClientes ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <h2 class="text-info">👥</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ventas: Últimos 7 Días</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="miGraficoVentas" style="width: 100%; height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-header bg-danger text-white">
                    <h6 class="m-0 fw-bold"><i class="bi bi-exclamation-triangle-fill"></i> Alerta: Stock Bajo</h6>
                </div>
                <div class="card-body">
                    <?php if(empty($bajoStock)): ?>
                        <p class="text-success mb-0">¡Todo bien! No hay productos con stock crítico.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Código</th>
                                        <th>Stock Actual</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($bajoStock as $p): ?>
                                        <tr>
                                            <td><?= esc($p['nombre']) ?></td>
                                            <td><?= esc($p['codigo']) ?></td>
                                            <td class="text-danger fw-bold"><?= $p['stock'] ?></td>
                                            <td>
                                                <a href="<?= base_url('productos') ?>" class="btn btn-xs btn-primary btn-sm">
                                                    Reponer
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Obtenemos los datos que mandó el controlador
        const etiquetas = <?= $graficoFechas ?>; // Array de fechas
        const datos = <?= $graficoMontos ?>;     // Array de montos

        const ctx = document.getElementById('miGraficoVentas').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar', // Tipo de gráfico: bar, line, pie
            data: {
                labels: etiquetas,
                datasets: [{
                    label: 'Total Vendido (S/)',
                    data: datos,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
<?= $this->endSection() ?>