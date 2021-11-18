<?php

namespace App\Filters;

use App\Models\Usuario;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class BasicauthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        //print_r($_SERVER);
        $username = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : "";
        $password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : "";

        $user = new Usuario();
        $res = $user->builder()
            ->select('senha')
            ->getwhere(['email'=>$username])
            ->getRowArray();

        if($res==null || !password_verify($password, $res['senha'])){

            header("Content-type: application/json");

            echo json_encode(array(
                "status" => false,
                "message" => "Credenciais invalidas"
            ));
            die;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}