<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Imagens extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_imagem' => [
                'type' => 'bigserial',
            ],
            'conteudo' => [
                'type' => 'bytea',
                'null' => false,
            ],
            'id_pessoa' => [
                'type' => 'int',
                'null' => true,
            ],
            'id_usuario' => [
                'type' => 'int',
                'null' => true,
            ],
            'id_animal' => [
                'type' => 'int',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id_imagem');
        $this->forge->addForeignKey('id_pessoa','pessoas','id_pessoa','cascade','cascade');
        $this->forge->addForeignKey('id_usuario','usuarios','id_usuario','cascade','cascade');
        $this->forge->addForeignKey('id_animal','animais','id_animal','cascade','cascade');
        $this->forge->createTable('imagens');
    }

    public function down()
    {
        $this->forge->dropTable('imagens');
    }
}
