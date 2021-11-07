<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pessoas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pessoa' => [
                'type' => 'bigserial',
            ],
            'nome' => [
                'type' => 'varchar',
                'constraint' => '100',
                'null' => false,
            ],
            'nascimento' => [
                'type' => 'date',
                'null' => false,
            ],
            'cpf' => [
                'type' => 'varchar',
                'constraint' => '11',
                'null' => false,
            ],
            'sexo' => [
                'type' => 'char',
                'null' => false,
            ],
            'telefone' => [
                'type' => 'varchar',
                'constraint' => 20,
                'null' => false,
            ],
            'id_endereco' => [
                'type' => 'int',
                'null' => false,
            ],
        ]);
        $this->forge->addPrimaryKey('id_pessoa');
        $this->forge->addUniqueKey('cpf');
        $this->forge->addForeignKey('id_endereco','enderecos','id_endereco','cascade','cascade');
        $this->forge->createTable('pessoas');
    }

    public function down()
    {
        $this->forge->dropTable('pessoas');
    }
}
