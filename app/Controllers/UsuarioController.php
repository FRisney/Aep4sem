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
            return $this->fail('Conteudo nao e json', 406);
        }

        $data = $this->request->getJSON(true);

        $endereco = new Endereco();
        if($this->pessoa->getWhere(['cpf'=>$data['pessoa']['cpf']])->getResultArray()){
            return $this->failResourceExists('Pessoa ja cadastrada');
        }
        if($this->usuario->getWhere(['email'=>$data['usuario']['email']])->getResultArray()){
            return $this->failResourceExists('Email ja cadastrado');
        }

        try {
            $endereco->insert($data['endereco']);
            $data['pessoa']['id_endereco'] = $data['endereco']['id_endereco'] = $endereco->getInsertID();
            $this->pessoa->insert($data['pessoa']);
            $data['usuario']['senha'] = password_hash($data['usuario']['senha'], PASSWORD_BCRYPT);
            $data['usuario']['id_pessoa'] = $data['pessoa']['id_pessoa'] = $this->pessoa->getInsertID();
            $this->usuario->insert($data['usuario']);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }

        return $this->respondCreated($data);
    }

    public function detalhes($id){
        $usr = $this->usuario->find($id);
        if (!$usr)
            return $this->failResourceGone('usr');
        $psa = $this->pessoa->find($usr['id_pessoa']);
        if(!$psa)
            return $this->failResourceGone('pessoa');
        $end = (new Endereco())->find($psa['id_endereco']);
        if(!$end)
            return $this->failResourceGone('endereco');

        return $this->respond([
            'usuario'=>$usr,
            'pessoa'=>$psa,
            'endereco'=>$end,
        ]);
    }

    public function update($id)
    {
        if (!$this->request->hasHeader('Content-Type') || $this->request->header('Content-Type')->getValue() != 'application/json'){
            return $this->fail('Nao foi possivel salvar endereco', 406);
        }

        $data = $this->request->getJSON(true);

        $usr = $this->usuario->find($id);
        if(!$usr) return $this->failResourceGone('user');

        $psa = $this->pessoa->find($usr['id_pessoa']);
        if(!$psa) return $this->failResourceGone('pessoa');

        try{
            $data['usuario']['senha'] = password_hash($data['usuario']['senha'], PASSWORD_BCRYPT);
            $this->usuario->update($id, $data['usuario']);
            $this->pessoa->update($usr['id_pessoa'],$data['pessoa']);
            $endereco = new Endereco();
            $endereco->update($psa['id_endereco'],$data['endereco']);
        }catch(\Throwable $th){
            return $this->fail($th->getMessage());
        }

        return $this->respond($data, 200);
    }

    public function delete($id)
    {
        $usr = $this->usuario->find($id);
        if (!$usr)
            return $this->failResourceGone('usr');
        $psa = $this->pessoa->find($usr['id_pessoa']);
        if(!$psa)
            return $this->failResourceGone('pessoa');
        $end = (new Endereco())->find($psa['id_endereco']);
        if(!$end)
            return $this->failResourceGone('endereco');

        $this->usuario->delete($usr['id_usuario']);
        $this->pessoa->delete($psa['id_pessoa']);
        (new Endereco())->delete($end['id_endereco']);

        return $this->respond([
            'usuario'=>$usr,
            'pessoa'=>$psa,
            'endereco'=>$end,
        ]);
    }
}
