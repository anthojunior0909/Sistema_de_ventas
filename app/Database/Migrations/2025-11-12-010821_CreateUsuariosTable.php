<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsuariosTable extends Migration
{
    // El método UP se ejecuta cuando corremos la migración (CREA la tabla)
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true, // No pueden haber dos usuarios con el mismo nombre
            ],
            'password_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => '255', // Para guardar la contraseña encriptada
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'vendedor'], // Roles permitidos
                'default'    => 'vendedor',
            ],
        ]);

        // Definimos 'id' como la llave primaria
        $this->forge->addKey('id', true);
        
        // Creamos la tabla
        $this->forge->createTable('usuarios');
    }

    // El método DOWN se ejecuta cuando revertimos la migración (BORRA la tabla)
    public function down()
    {
        $this->forge->dropTable('usuarios');
    }
}