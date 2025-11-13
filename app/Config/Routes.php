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