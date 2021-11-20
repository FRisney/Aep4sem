<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Animal;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Files\File;

class AnimalController extends BaseController
{
    use ResponseTrait;

    protected $model;

    public function __construct()
    {
        $this->model = new Animal();
    }

    public function list(){
        $from = $this->request->getGet('from',FILTER_VALIDATE_INT) ?? 1;
        $qtd = $this->request->getGet('qtd',FILTER_VALIDATE_INT) ?? 10;

        $data = $this->model
            ->select('animais.id_animal, nome, peso, idade, porte, especie, status, conteudo')
            ->join('imagens','imagens.id_animal = animais.id_animal','left')
            ->get($qtd,$from)
            ->getResultArray();

        if(!$data || empty($data)){
            $this->respondNoContent();
        }
        return $this->respond($data);
    }

    public function detalhes($id){
        if(empty($id))
            return $this->fail('Sem id do animal', 406);

        $anim = $this->model->find($id);

        if(!$anim) return $this->respondNoContent('nao encontrado');

        return $this->respond($anim);
    }

    public function new(){
        if (!$this->request->hasHeader('Content-Type') || $this->request->header('Content-Type')->getValue() != 'application/json'){
            return $this->fail('Conteudo nao e json', 406);
        }

        $data = $this->request->getJSON(true);

        try {
            $id = $this->model->insert($data);
            $data['id_animal'] = $id;
        } catch (\Throwable $th){
            return $this->fail($th->getMessage());
        }

        return $this->respondCreated($data,200);
    }

    public function update($id){
        if (!$this->request->hasHeader('Content-Type') || $this->request->header('Content-Type')->getValue() != 'application/json'){
            return $this->fail('Conteudo nao e json', 406);
        }

        $data = $this->request->getJSON(true);

        if(empty($id))
            return $this->fail('Sem id do animal', 406);

        if(!$this->model->find($id))
            return $this->respondNoContent('nao encontrado');

        try {
            $this->model->update($id,$data);
        } catch (\Throwable $th){
            return $this->fail($th->getMessage());
        }

        return $this->respondUpdated($data,200);
    }

    public function delete($id){
        if(empty($id))
            return $this->fail('Sem id do animal', 406);

        $animal = $this->model->find($id);
        if(!$animal)
            return $this->respondNoContent('nao encontrado');

        try {
            $this->model->delete($id);
        } catch (\Throwable $th){
            return $this->fail($th->getMessage());
        }

        return $this->respondDeleted($animal,200);
    }
}
