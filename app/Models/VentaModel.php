<?php

namespace App\Models;

use CodeIgniter\Model;

class VentaModel extends Model
{
    protected $table            = 'ventas';
    protected $primaryKey       = 'id';
    protected $allowedFields = ['cliente_id', 'usuario_id', 'sesion_id', 'total', 'metodo_pago'];
    protected $useTimestamps    = false; // Usamos el timestamp de la BD por defecto
}