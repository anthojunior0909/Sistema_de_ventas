<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table            = 'productos'; // Nombre de la tabla
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'codigo', 
        'nombre', 
        'precio_compra', 
        'precio_venta', 
        'stock'
    ]; // Campos permitidos

    // Opcional: Deshabilita timestamps
    protected $useTimestamps = false;
}