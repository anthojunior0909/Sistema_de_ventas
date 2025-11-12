<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVentasTable extends Migration
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
            'cliente_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => true, // Permite "venta al público general"
            ],
            'usuario_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true, // El vendedor que hizo la venta
            ],
            'total' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'metodo_pago' => [
                'type'       => 'ENUM',
                'constraint' => ['efectivo', 'tarjeta', 'otro'],
                'default'    => 'efectivo',
            ],
            'fecha' => [
                'type'       => 'TIMESTAMP',
                'default'    => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        
        $this->forge->addKey('id', true);
        
        // Añadimos las llaves foráneas
        $this->forge->addForeignKey('cliente_id', 'clientes', 'id', 'NO ACTION', 'SET NULL');
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'NO ACTION', 'NO ACTION');
        
        $this->forge->createTable('ventas');
    }

    public function down()
    {
        $this->forge->dropTable('ventas');
    }
}