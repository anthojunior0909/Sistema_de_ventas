<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">Mi Sistema POS</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="navbar-text me-3">
                            Hola, <?= session()->get('username') ?> (<?= session()->get('role') ?>)
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-danger" href="<?= base_url('logout') ?>">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col">
                <div class="alert alert-success" role="alert">
                    ¡Bienvenido al sistema! Has iniciado sesión correctamente.
                </div>
                <h1>Dashboard Principal</h1>
                <p>Aquí irá el contenido principal del sistema (gráficos, accesos directos, etc.).</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>