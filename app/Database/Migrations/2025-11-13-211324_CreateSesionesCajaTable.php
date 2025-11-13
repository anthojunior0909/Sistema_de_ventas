<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSesionesCajaTable extends Migration
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
            'usuario_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'monto_inicial' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'monto_final' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'fecha_apertura' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'fecha_cierre' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'estado' => [
                'type'       => 'ENUM',
                'constraint' => ['abierta', 'cerrada'],
                'default'    => 'abierta',
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sesiones_caja');
    }

    public function down()
    {
        $this->forge->dropTable('sesiones_caja');
    }
}