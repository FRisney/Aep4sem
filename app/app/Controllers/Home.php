<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
       $db = db_connect();
       d($db->listTables());
        return view('welcome_message');
    }
}
