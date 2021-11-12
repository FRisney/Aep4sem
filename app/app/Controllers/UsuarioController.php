<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Usuario;
use CodeIgniter\HTTP\Response;

class UsuarioController extends BaseController
{

    public function listUsers(){
        $usuarioModel = new Usuario();
        $users = $usuarioModel->builder->get();
        d($users);
        return new Response($users);
    }
}
