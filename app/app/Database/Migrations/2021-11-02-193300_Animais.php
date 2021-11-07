<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Animais extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_animal' => [
                'type' => 'bigserial',
            ],
            'nome' => [
                'type' => 'varchar',
                'constraint' => '100',
                'null' => false,
            ],
            'peso' => [
                'type' => 'real',
                'null' => false,
            ],
            'idade' => [
                'type' => 'int',
                'null' => false,
            ],
            'porte' => [
                'type' => 'varchar',
                'constraint' => 45,
                'null' => false,
            ],
            'especie' => [
                'type' => 'varchar',
                'constraint' => 45,
                'null' => false,
            ],
            'staus' => [
                'type' => 'int',
                'null' => false,
                'default' => 0,
            ],
        ]);
        $this->forge->addPrimaryKey('id_animal');
        $this->forge->createTable('animais');
    }

    public function down()
    {
        $this->forge->dropTable('animais');
    }
}
