<?php

namespace App\Models;

use CodeIgniter\Model;

class CajaModel extends Model
{
    protected $table            = 'sesiones_caja';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['usuario_id', 'monto_inicial', 'monto_final', 'fecha_apertura', 'fecha_cierre', 'estado'];
    protected $useTimestamps    = false;

    // Función auxiliar para verificar si hay caja abierta por el usuario
    public function obtenerCajaAbierta($usuarioId)
    {
        return $this->where('usuario_id', $usuarioId)
                    ->where('estado', 'abierta')
                    ->first();
    }
}