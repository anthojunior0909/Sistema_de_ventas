<!DOCTYPE html>
<html lang="es" data-bs-theme="light"> <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> | Sistema POS</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            overflow-x: hidden;
        }
        .wrapper {
            display: flex;
            width: 100%;
            flex-grow: 1;
            align-items: stretch;
        }
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #212529; /* Dark fix */
            color: #fff;
            transition: all 0.3s;
        }
        #sidebar.active {
            margin-left: -250px;
        }
        #sidebar .list-group-item {
            background: #212529;
            color: #ccc;
            border: none;
        }
        #sidebar .list-group-item:hover, #sidebar .list-group-item.active {
            background: #0d6efd;
            color: #fff;
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
    <nav id="sidebar" class="p-3">
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
        <nav id="topNavbar" class="navbar navbar-expand-lg navbar-light bg-light mb-4 shadow-sm">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-primary">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto align-items-center">
                        
                        <li class="nav-item me-3">
                            <button class="btn btn-outline-secondary rounded-circle border-0" id="btnTheme">
                                <i class="bi bi-moon-stars-fill" id="iconTheme"></i>
                            </button>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('perfil') ?>" class="nav-link me-3 fw-bold">
                                Hola, <?= session()->get('username') ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-danger btn-sm" href="<?= base_url('logout') ?>">
                                <i class="bi bi-box-arrow-right"></i> Salir
                            </a>
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
        // Toggle Sidebar
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });

        // --- LÓGICA TEMA OSCURO ---
        const htmlElement = document.documentElement;
        const switchButton = document.getElementById('btnTheme');
        const iconTheme = document.getElementById('iconTheme');
        const topNavbar = document.getElementById('topNavbar');

        // Función para aplicar tema
        const setTheme = (theme) => {
            htmlElement.setAttribute('data-bs-theme', theme);
            localStorage.setItem('theme', theme);

            if (theme === 'dark') {
                iconTheme.className = 'bi bi-sun-fill'; // Icono Sol
                // Cambiar Barra Superior a Oscura
                topNavbar.classList.remove('navbar-light', 'bg-light');
                topNavbar.classList.add('navbar-dark', 'bg-dark');
            } else {
                iconTheme.className = 'bi bi-moon-stars-fill'; // Icono Luna
                // Cambiar Barra Superior a Clara
                topNavbar.classList.remove('navbar-dark', 'bg-dark');
                topNavbar.classList.add('navbar-light', 'bg-light');
            }
        }

        // Detectar preferencia guardada
        const savedTheme = localStorage.getItem('theme') || 'light';
        setTheme(savedTheme);

        // Click en el botón
        switchButton.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
        });
    });
</script>

<?= $this->renderSection('scripts') ?>

</body>
</html>