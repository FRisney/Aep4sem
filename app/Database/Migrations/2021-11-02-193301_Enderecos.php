<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Enderecos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_endereco' => [
                'type' => 'bigserial',
            ],
            'rua' => [
                'type' => 'varchar',
                'constraint' => 100,
                'null' => false,
            ],
            'cidade' => [
                'type' => 'varchar',
                'constraint' => 11,
                'null' => false,
            ],
            'uf' => [
                'type' => 'char',
                'constraint' => 2,
                'null' => false,
            ],
            'cep' => [
                'type' => 'char',
                'constraint' => 8,
                'null' => false,
            ],
            'pais' => [
                'type' => 'varchar',
                'constraint' => 20,
                'null' => false,
            ],
        ]);
        $this->forge->addPrimaryKey('id_endereco');
        $this->forge->createTable('enderecos');
    }

    public function down()
    {
        $this->forge->dropTable('enderecos');
    }
}
