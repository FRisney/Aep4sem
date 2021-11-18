<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Adocao;
use App\Models\Animal;
use App\Models\Pessoa;
use App\Models\Usuario;
use CodeIgniter\API\ResponseTrait;

class AdocaoController extends BaseController

{
    use ResponseTrait;
    protected $usuario;
    protected $pessoa;
    protected $animal;
    protected $model;

    public function __construct()
    {
        $this->usuario = new Usuario();
        $this->pessoa = new Pessoa();
        $this->animal = new Animal();
        $this->model = new Adocao();
    }

    public function list(){
        $from = $this->request->getGet('from',FILTER_VALIDATE_INT) ?? 1;
        $qtd = $this->request->getGet('qtd',FILTER_VALIDATE_INT) ?? 10;

        $data = $this->model->getWhere(['id_adocao >='=>$from],$qtd)->getResultArray();

        if(!$data || empty($data)){
            $this->respondNoContent();
        }
        return $this->respond($data);
    }

    public function new()
    {
        if (!$this->request->hasHeader('Content-Type') || $this->request->header('Content-Type')->getValue() != 'application/json'){
            return $this->fail('Conteudo nao e json', 406);
        }

        $data = $this->request->getJSON(true);

        if(!$this->pessoa->find($data['id_pessoa'])){
            return $this->failResourceGone('Pessoa nao cadastrada');
        }
        if(!$this->usuario->find($data['id_usuario'])){
            return $this->failResourceGone('Usuario nao cadastrado');
        }
        if(!$this->animal->find($data['id_animal'])){
            return $this->failResourceGone('Animal nao cadastrado');
        }

        if($data['datahora'] == null || empty($data['datahora'])){
            return $this->fail('Sem status');
        }
        if($data['datahora'] == null || empty($data['datahora'])){
            date_default_timezone_set('America/Sao_Paulo');
            $data['datahora'] = date('now');
        }

        try {
            $this->model->insert($data);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }

        return $this->respondCreated($data);
    }

    public function detalhes($id){
        $adc = $this->model->find($id);
        if (!$adc)
            return $this->failResourceGone('adc');
        $usr = $this->usuario->find($adc['id_usuario']);
        if (!$usr)
            return $this->failResourceGone('usr');
        $psa = $this->pessoa->find($adc['id_pessoa']);
        if(!$psa)
            return $this->failResourceGone('pessoa');
        $anl = $this->animal->find($adc['id_animal']);
        if(!$anl)
            return $this->failResourceGone('animal');

        return $this->respond([
            'datahora'=>$adc['datahora'],
            'status'=>$adc['status'],
            'usuario'=>$usr,
            'pessoa'=>$psa,
            'animal'=>$anl,
        ]);
    }

    public function update($id)
    {
        if (!$this->request->hasHeader('Content-Type') || $this->request->header('Content-Type')->getValue() != 'application/json'){
            return $this->fail('Conteudo nao e json', 406);
        }

        $data = $this->request->getJSON(true);

        $adc = $this->model->find($id);
        if (!$adc)
            return $this->failResourceGone('adc');
        $usr = $this->usuario->find($adc['id_usuario']);
        if (!$usr)
            return $this->failResourceGone('usr');
        $psa = $this->pessoa->find($adc['id_pessoa']);
        if(!$psa)
            return $this->failResourceGone('pessoa');
        $anl = $this->animal->find($adc['id_animal']);
        if(!$anl)
            return $this->failResourceGone('animal');

        try{
            $this->model->update($id,$data);
        }catch(\Throwable $th){
            return $this->fail($th->getMessage());
        }

        return $this->respond($data, 200);
    }

    public function delete($id)
    {
        $adc = $this->model->find($id);
        if (!$adc)
            return $this->failResourceGone('usr');

        $this->model->delete($id);
        return $this->respond($adc);
    }
}
