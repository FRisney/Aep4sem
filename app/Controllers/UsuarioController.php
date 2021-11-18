<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Endereco;
use App\Models\Pessoa;
use App\Models\Usuario;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class UsuarioController extends BaseController
{
    use ResponseTrait;
    protected $usuario;
    protected $pessoa;

    public function __construct()
    {
        $this->usuario = new Usuario();
        $this->pessoa = new Pessoa();
    }

    public function new()
    {
        if (!$this->request->hasHeader('Content-Type') || $this->request->header('Content-Type')->getValue() != 'application/json'){
            return $this->fail('Nao foi possivel salvar endereco', 406);
        }

        $data = $this->request->getJSON(true);

        $endereco = new Endereco();
        if(!$endereco->insert($data['endereco'])){
            return $this->fail('Nao foi possivel salvar endereco', 400);
        }

        $data['pessoa']['id_endereco'] = $endereco->getInsertID();
        $pessoa = new Pessoa();
        if(!$pessoa->insert( $data['pessoa'] ))
        {
            return $this->fail('Nao foi possivel salvar pessoa', 400);
        }

        $data['usuario']['senha'] = password_hash($data['usuario']['senha'], PASSWORD_BCRYPT);
        $data['usuario']['id_pessoa'] = $pessoa->getInsertID();
        if(!$this->usuario->insert( $data['usuario'] ))
        {
            return $this->fail('Nao foi possivel salvar usuario', 400);
        }

        return $this->respondCreated($data);
    }

    public function update()
    {
        if (!$this->request->hasHeader('Content-Type') || $this->request->header('Content-Type')->getValue() != 'application/json'){
            return $this->fail('Nao foi possivel salvar endereco', 406);
        }

        $data = $this->request->getJSON(true);

        $endereco = new Endereco();
        if(!$endereco->update($data['endereco'])){
            return $this->fail('Nao foi possivel salvar endereco', 400);
        }

        $pessoa = new Pessoa();
        if(!$pessoa->update( $data['pessoa'] ))
        {
            return $this->fail('Nao foi possivel salvar pessoa', 400);
        }

        $data['usuario']['senha'] = password_hash($data['usuario']['senha'], PASSWORD_BCRYPT);
        if(!$this->usuario->update( $data['usuario'] ))
        {
            return $this->fail('Nao foi possivel salvar usuario', 400);
        }

        return $this->respond($data, 200);
    }

//    public function delete()
//    {
//       $this->usuario->delete($this->request->get)
//    }
}
