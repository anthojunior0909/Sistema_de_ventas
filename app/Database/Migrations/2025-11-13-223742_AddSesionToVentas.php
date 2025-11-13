<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSesionToVentas extends Migration
{
    public function up()
    {
        // 1. Agregar la columna
        $fields = [
            'sesion_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => true, // Permitimos null por si hay ventas viejas
                'after'      => 'usuario_id' // Orden visual
            ],
        ];
        $this->forge->addColumn('ventas', $fields);

        // 2. Agregar la Llave Foránea (Relación)
        // Nota: Usamos SQL directo para asegurar compatibilidad al alterar tabla existente
        $this->db->query('ALTER TABLE ventas ADD CONSTRAINT ventas_sesion_id_foreign FOREIGN KEY (sesion_id) REFERENCES sesiones_caja(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        // Borrar la llave foránea y la columna si revertimos
        $this->db->query('ALTER TABLE ventas DROP FOREIGN KEY ventas_sesion_id_foreign');
        $this->forge->dropColumn('ventas', 'sesion_id');
    }
}