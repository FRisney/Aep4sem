<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Endereco;
use App\Models\Pessoa;
use App\Models\Usuario;
use CodeIgniter\HTTP\ResponseInterface;

class UsuarioController extends BaseController
{
    protected $usuario;
    protected $pessoa;

    public function __construct()
    {
        $this->usuario = new Usuario();
        $this->pessoa = new Pessoa();
    }

    public function new()
    {
        if ($this->request->header('Content-Type')->getValue() != 'application/json'){
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NOT_ACCEPTABLE)
                ->send();
        }

        $data = $this->request->getJSON(true);

        $endereco = new Endereco();
        if(!$endereco->insert($data['endereco'])){
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setBody('Nao foi possivel salvar endereco')
                ->send();
        }

        $data['pessoa']['id_endereco'] = $endereco->getInsertID();
        $pessoa = new Pessoa();
        if(!$pessoa->insert( $data['pessoa'] ))
        {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setBody('Nao foi possivel salvar pessoa')
                ->send();
        }

        $data['usuario']['senha'] = password_hash($data['usuario']['senha'], PASSWORD_BCRYPT);
        $data['usuario']['id_pessoa'] = $pessoa->getInsertID();
        if(!$this->usuario->insert( $data['usuario'] ))
        {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setBody('Nao foi possivel salvar usuario')
                ->send();
        }

        return $this->response
            ->setStatusCode(ResponseInterface::HTTP_OK)
            ->setBody(null)
            ->setJSON($data)
            ->send();
    }

    public function update()
    {
        if ($this->request->header('Content-Type')->getValue() != 'application/json'){
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NOT_ACCEPTABLE)
                ->send();
        }

        $data = $this->request->getJSON(true);

        $endereco = new Endereco();
        if(!$endereco->update($data['endereco'])){
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setBody('Nao foi possivel salvar endereco')
                ->send();
        }

        $pessoa = new Pessoa();
        if(!$pessoa->update( $data['pessoa'] ))
        {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setBody('Nao foi possivel salvar pessoa')
                ->send();
        }

        $data['usuario']['senha'] = password_hash($data['usuario']['senha'], PASSWORD_BCRYPT);
        if(!$this->usuario->update( $data['usuario'] ))
        {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setBody('Nao foi possivel salvar usuario')
                ->send();
        }

        return $this->response
            ->setStatusCode(ResponseInterface::HTTP_OK)
            ->setBody(null)
            ->setJSON($data)
            ->send();
    }

    public function delete()
    {
       $this->usuario->delete($this->request->get)
    }
}
