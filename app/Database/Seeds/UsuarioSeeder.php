<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UsuarioModel; // <-- Importamos el modelo

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $model = new UsuarioModel();

        // Datos del usuario admin
        $data = [
            'username' => 'admin',
            'password_hash' => password_hash('admin123', PASSWORD_DEFAULT), // <-- Encriptamos la contraseña
            'role'     => 'admin'
        ];

        // Usamos el modelo para insertar los datos
        $model->insert($data);
    }
}