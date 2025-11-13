<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> | Sistema POS</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            overflow-x: hidden; /* Evita scroll horizontal al ocultar barra */
        }
        .wrapper {
            display: flex;
            width: 100%;
            flex-grow: 1;
            align-items: stretch; /* Estira los elementos */
        }
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #343a40;
            color: #fff;
            transition: all 0.3s;
        }
        
        /* --- NUEVO: Clase para ocultar la barra --- */
        #sidebar.active {
            margin-left: -250px;
        }
        /* ----------------------------------------- */

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
            transition: all 0.3s;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <nav id="sidebar" class="bg-dark text-white p-3">
        <h4><a href="<?= base_url('dashboard') ?>" class="text-white text-decoration-none">Mi Sistema POS</a></h4>
        
        <div class="list-group list-group-flush mt-3">
            <a href="<?= base_url('dashboard') ?>" class="list-group-item list-group-item-action">Dashboard</a>
            <a href="<?= base_url('ventas') ?>" class="list-group-item list-group-item-action">Ventas / Historial</a>
            <a href="<?= base_url('productos') ?>" class="list-group-item list-group-item-action">Productos</a>
            <a href="<?= base_url('clientes') ?>" class="list-group-item list-group-item-action">Clientes</a>
            <a href="<?= base_url('caja') ?>" class="list-group-item list-group-item-action">Control de Caja</a>
            <a href="<?= base_url('reportes') ?>" class="list-group-item list-group-item-action">Reportes</a>
            <?php if(session()->get('role') == 'admin'): ?>
                <a href="<?= base_url('usuarios') ?>" class="list-group-item list-group-item-action">Usuarios</a>
            <?php endif; ?>
        </div>
    </nav>

    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 shadow-sm">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-info text-white">
                    <span>&#9776;</span>
                </button>
                
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a href="<?= base_url('perfil') ?>" class="nav-link me-3 text-dark">
                                Hola, <strong><?= session()->get('username') ?></strong> (Perfil)
                            </a>
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
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
</script>

<?= $this->renderSection('scripts') ?>

</body>
</html>