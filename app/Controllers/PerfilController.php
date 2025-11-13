<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class PerfilController extends BaseController
{
    // 1. Mostrar el formulario
    public function index()
    {
        return view('perfil/index');
    }

    // 2. Procesar el cambio de contraseña
    public function updatePassword()
    {
        // Validación de campos
        if (!$this->validate([
            'password_actual' => 'required',
            'password_nueva'  => 'required|min_length[6]',
            'password_confirm'=> 'required|matches[password_nueva]'
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $usuarioModel = new UsuarioModel();
        $userId = session()->get('user_id');
        $usuario = $usuarioModel->find($userId);

        // 1. Verificar que la contraseña actual sea correcta
        $passwordActual = $this->request->getPost('password_actual');
        
        if (!password_verify($passwordActual, $usuario['password_hash'])) {
            return redirect()->back()->with('error', 'La contraseña actual es incorrecta.');
        }

        // 2. Actualizar con la nueva contraseña (hasheada)
        $passwordNueva = $this->request->getPost('password_nueva');
        
        $usuarioModel->update($userId, [
            'password_hash' => password_hash($passwordNueva, PASSWORD_DEFAULT)
        ]);

        return redirect()->to('perfil')->with('success', '¡Contraseña actualizada correctamente!');
    }
}