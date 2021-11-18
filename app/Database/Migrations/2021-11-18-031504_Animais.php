<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Animais extends Migration
{
    public function up()
    {
        $fields = [
            'staus' => [
                'name' => 'status',
            ],
        ];
        $this->forge->modifyColumn('animais', $fields);
    }

    public function down()
    {
        $fields = [
            'status' => [
                'name' => 'staus',
            ],
        ];
        $this->forge->modifyColumn('animais', $fields);
    }
}
