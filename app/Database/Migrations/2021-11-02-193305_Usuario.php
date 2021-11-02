<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Usuarios extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_usuario' => [
                'type' => 'bigserial',
            ],
            'email' => [
                'type' => 'varchar',
                'constraint' => 100,
                'null' => false,
            ],
            'senha' => [
                'type' => 'char',
                'constraint' => 60,
                'null' => false,
            ],
            'role' => [
                'type' => 'varchar',
                'constraint' => 45,
                'null' => false,
            ],
            'id_pessoa' => [
                'type' => 'int',
                'null' => false,
            ],
        ]);
        $this->forge->addPrimaryKey('id_usuario');
        $this->forge->addUniqueKey('email');
        $this->forge->addForeignKey('id_pessoa','pessoas','id_pessoa','cascade','cascade');
        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        $this->forge->dropTable('usuarios');
    }
}
