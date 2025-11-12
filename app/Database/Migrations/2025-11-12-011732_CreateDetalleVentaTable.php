<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetalleVentaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'venta_id' => [ // A qué venta pertenece
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'producto_id' => [ // Qué producto es
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => true, // Para el caso de que un producto sea eliminado
            ],
            'cantidad' => [
                'type'       => 'INT',
                'constraint' => 5,
            ],
            'precio_unitario' => [ // Guardamos el precio al momento de la venta
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
        ]);
        
        $this->forge->addKey('id', true);

        // Llaves foráneas
        $this->forge->addForeignKey('venta_id', 'ventas', 'id', 'CASCADE', 'CASCADE'); // Si se borra la venta, se borra el detalle
        $this->forge->addForeignKey('producto_id', 'productos', 'id', 'NO ACTION', 'SET NULL'); // Si se borra el producto, el detalle queda pero sin link

        $this->forge->createTable('detalle_venta');
    }

    public function down()
    {
        $this->forge->dropTable('detalle_venta');
    }
}