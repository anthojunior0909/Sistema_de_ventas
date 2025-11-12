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
