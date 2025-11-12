<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> | Sistema POS</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css" rel="stylesheet">
    
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .wrapper {
            display: flex;
            width: 100%;
            flex-grow: 1;
        }
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #343a40; /* Color oscuro */
            color: #fff;
            transition: all 0.3s;
        }
        #sidebar .list-group-item {
            background: #343a40;
            color: #fff;
            border: none;
        }
        #sidebar .list-group-item:hover {
            background: #495057;
        }
        #content {
            width: 100%;
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <nav id="sidebar" class="bg-dark text-white p-3">
        <h4><a href="<?= base_url('dashboard') ?>" class="text-white text-decoration-none">Mi Sistema POS</a></h4>
        
        <div class="list-group list-group-flush mt-3">
            <a href="<?= base_url('dashboard') ?>" class="list-group-item list-group-item-action active">
                Dashboard
            </a>
            <a href="#" class="list-group-item list-group-item-action">
                Ventas
            </a>
            <a href="<?= base_url('productos') ?>" class="list-group-item list-group-item-action">
                Productos
            </a>
            <a href="#" class="list-group-item list-group-item-action">
                Clientes
            </a>
            <a href="#" class="list-group-item list-group-item-action">
                Reportes
            </a>
            <?php if(session()->get('role') == 'admin'): ?>
                <a href="#" class="list-group-item list-group-item-action">
                    Usuarios
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <div id="content">
        
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 shadow-sm">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-info">
                    <span>&#9776;</span>
                </button>
                
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <span class="navbar-text me-3">
                                Hola, **<?= session()->get('username') ?>**
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-danger" href="<?= base_url('logout') ?>">Cerrar Sesión</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            <?= $this->renderSection('content') ?>
        </main>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>

<?= $this->renderSection('scripts') ?>

</body>
</html>