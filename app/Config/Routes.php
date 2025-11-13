<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('login', 'AuthController::login'); // Muestra el formulario de login
$routes->post('auth/attemptLogin', 'AuthController::attemptLogin'); // Procesa el formulario
$routes->get('logout', 'AuthController::logout'); // Cierra la sesión
// --- Ruta del Dashboard ---
$routes->get('dashboard', 'DashboardController::index');
$routes->resource('productos', ['controller' => 'ProductosController']);
$routes->resource('clientes', ['controller' => 'ClientesController']);
// --- Rutas de Ventas ---
$routes->get('ventas', 'VentasController::index'); // Historial
$routes->get('ventas/nueva', 'VentasController::new');
$routes->post('ventas/guardar', 'VentasController::create');
$routes->get('ventas/detalle/(:num)', 'VentasController::show/$1'); // Para ver detalle
$routes->get('ventas/pdf/(:num)', 'VentasController::generarPdf/$1');
// --- Rutas de Caja ---
$routes->get('caja', 'CajaController::index');
$routes->post('caja/abrir', 'CajaController::abrir');
$routes->post('caja/cerrar', 'CajaController::cerrar');
// Reportes
$routes->get('reportes', 'ReportesController::index');
$routes->post('reportes/excel', 'ReportesController::generarExcel');
// --- Perfil ---
$routes->get('perfil', 'PerfilController::index');
$routes->post('perfil/password', 'PerfilController::updatePassword');
// Usuarios
$routes->get('usuarios', 'UsuariosController::index');
$routes->post('usuarios/guardar', 'UsuariosController::create');
$routes->post('usuarios/eliminar/(:num)', 'UsuariosController::delete/$1');