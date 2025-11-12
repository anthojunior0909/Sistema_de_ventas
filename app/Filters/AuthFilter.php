<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session(); // Obtenemos la sesión

        // Si no existe la variable 'isLoggedIn' en la sesión...
        if (!$session->get('isLoggedIn')) {
            // ...lo redirigimos al login (que definiremos como 'auth/login')
            return redirect()->to('auth/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No necesitamos hacer nada después
    }
}