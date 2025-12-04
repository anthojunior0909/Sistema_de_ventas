<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> | Sistema POS</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
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
                <ul class="navbar-nav ms-auto align-items-center"> <li class="nav-item me-3">
                        <button class="btn btn-sm btn-outline-secondary rounded-circle" id="btnTheme">
                            <i class="bi bi-moon-fill" id="iconTheme"></i>
                        </button>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('perfil') ?>" class="nav-link me-3 text-dark" id="userLabel">
                            Hola, <strong><?= session()->get('username') ?></strong> (Perfil)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-danger btn-sm" href="<?= base_url('logout') ?>">Cerrar Sesión</a>
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
<script>
    // 1. Lógica para manejar el tema
    const htmlElement = document.documentElement;
    const switchButton = document.getElementById('btnTheme');
    const iconTheme = document.getElementById('iconTheme');
    const userLabel = document.getElementById('userLabel'); // Para corregir el color del texto "Hola..."

    // Función para aplicar el tema
    const setTheme = (theme) => {
        htmlElement.setAttribute('data-bs-theme', theme);
        localStorage.setItem('theme', theme);
        
        // Cambiar ícono y colores específicos
        if (theme === 'dark') {
            iconTheme.classList.replace('bi-moon-fill', 'bi-sun-fill');
            switchButton.classList.replace('btn-outline-secondary', 'btn-outline-light');
            if(userLabel) userLabel.classList.replace('text-dark', 'text-light');
        } else {
            iconTheme.classList.replace('bi-sun-fill', 'bi-moon-fill');
            switchButton.classList.replace('btn-outline-light', 'btn-outline-secondary');
            if(userLabel) userLabel.classList.replace('text-light', 'text-dark');
        }
    }

    // 2. Detectar preferencia al cargar
    const savedTheme = localStorage.getItem('theme');
    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    
    // Aplicar el tema guardado o el del sistema
    setTheme(savedTheme || systemTheme);

    // 3. Evento Click
    switchButton.addEventListener('click', () => {
        const currentTheme = htmlElement.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        setTheme(newTheme);
    });
</script>
</body>
</html>