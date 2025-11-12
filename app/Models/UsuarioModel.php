<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios'; // Nombre de tu tabla
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['username', 'password_hash', 'role']; // Campos que permitimos modificar

    // Opcional: Deshabilita timestamps si no los usas en esta tabla
    protected $useTimestamps = false; 
}