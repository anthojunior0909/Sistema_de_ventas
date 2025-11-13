<?php

namespace App\Controllers;

use App\Models\UsuarioModel; // No olvides importar tu modelo

class AuthController extends BaseController
{
    /**
     * Muestra la vista de login
     */
    public function login()
    {
        // Si el usuario ya está logueado, redirigir al dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('dashboard');
        }
        
        // La vista 'auth/login' la crearemos en el siguiente paso
        return view('auth/login');
    }

    /**
     * Procesa el formulario de login
     */
    public function attemptLogin()
    {
        $session = session();
        $model = new UsuarioModel();

        // 1. Validar los datos (puedes añadir reglas más estrictas)
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return redirect()->back()->with('error', 'Usuario y contraseña son requeridos.');
        }

        // 2. Buscar al usuario en la base de datos
        $user = $model->where('username', $username)->first();

        // 3. Verificar al usuario y la contraseña
        // password_verify() compara el texto plano ($password) con el hash ($user['password_hash'])
        if ($user && password_verify($password, $user['password_hash'])) {
            
            // 4. ¡Éxito! Crear la sesión del usuario
            $sessionData = [
                'user_id'    => $user['id'],
                'username'   => $user['username'],
                'role'       => $user['role'],
                'isLoggedIn' => true,
            ];
            $session->set($sessionData);

            // 5. Redirigir al dashboard (lo crearemos pronto)
            return redirect()->to('dashboard');

        } else {
            // 6. ¡Fallo! Redirigir de vuelta al login con un error
            return redirect()->back()->with('error', 'Usuario o contraseña incorrectos.');
        }
    }

    /**
     * Cierra la sesión del usuario
     */
    public function logout()
    {
        session()->destroy(); // Destruye toda la data de la sesión
        return redirect()->to('auth/login');
    }
}