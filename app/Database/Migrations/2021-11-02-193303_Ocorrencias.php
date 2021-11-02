<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Ocorrencias extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_ocorrencia' => [
                'type' => 'bigserial',
            ],
            'titulo' => [
                'type' => 'varchar',
                'constraint' => 45,
                'null' => false,
            ],
            'descricao' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'datahora' => [
                'type' => 'timestamp',
                'null' => false,
            ],
            'id_endereco' => [
                'type' => 'int',
                'null' => false,
            ],
        ]);
        $this->forge->addPrimaryKey('id_ocorrencia');
        $this->forge->addForeignKey('id_endereco','enderecos','id_endereco','cascade','cascade');
        $this->forge->createTable('ocorrencias');
    }

    public function down()
    {
        $this->forge->dropTable('ocorrencias');
    }
}
