<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductosTable extends Migration
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
            'codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
                'null'       => true,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'precio_compra' => [ // Esencial para saber ganancias
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'precio_venta' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'stock' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 0,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('productos');
    }

    public function down()
    {
        $this->forge->dropTable('productos');
    }
}