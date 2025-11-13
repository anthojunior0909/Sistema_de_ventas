<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class UsuariosController extends BaseController
{
    // Filtro de seguridad interno: Solo admin pasa
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        if (session()->get('role') !== 'admin') {
            // Si no es admin, lo mandamos al dashboard y detenemos todo
            header('Location: ' . base_url('dashboard'));
            exit;
        }
    }

    public function index()
    {
        $model = new UsuarioModel();
        $data = ['usuarios' => $model->findAll()];
        return view('usuarios/index', $data);
    }

    public function create()
    {
        if ($this->request->isAJAX()) {
            $model = new UsuarioModel();

            // Validamos que el usuario no exista
            if (!$this->validate(['username' => 'is_unique[usuarios.username]'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'El nombre de usuario ya existe.']);
            }

            $data = [
                'username'      => $this->request->getPost('username'),
                'role'          => $this->request->getPost('role'),
                // La contraseña inicial será la misma que el usuario, luego ellos la cambian
                'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
            ];

            $model->insert($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Usuario creado correctamente.']);
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $model = new UsuarioModel();
            
            // Evitar borrarse a sí mismo
            if ($id == session()->get('user_id')) {
                return $this->response->setJSON(['success' => false, 'message' => 'No puedes eliminar tu propia cuenta.']);
            }

            $model->delete($id);
            return $this->response->setJSON(['success' => true, 'message' => 'Usuario eliminado.']);
        }
    }
}