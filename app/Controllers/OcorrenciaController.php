<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Endereco;
use App\Models\Ocorrencia;
use CodeIgniter\API\ResponseTrait;

class OcorrenciaController extends BaseController
{
    use ResponseTrait;
    protected $model;
    protected $endereco;

    public function __construct()
    {
        $this->model = new Ocorrencia();
        $this->endereco = new Endereco();
    }

    public function list(){
        $from = $this->request->getGet('from',FILTER_VALIDATE_INT) ?? 1;
        $qtd = $this->request->getGet('qtd',FILTER_VALIDATE_INT) ?? 10;

        $data = $this->model->getWhere(['id_ocorrencia >='=>$from],$qtd)->getResultArray();

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

        if($data['datahora'] == null || empty($data['datahora'])){
            date_default_timezone_set('America/Sao_Paulo');
            $data['datahora'] = date('now');
        }

        try {
            $this->endereco->insert($data['endereco']);
            $data['ocorrencia']['id_endereco'] = $this->endereco->getInsertID();
            $this->model->insert($data['ocorrencia']);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }

        return $this->respondCreated($data);
    }

    public function detalhes($id){
        $ocr = $this->model->find($id);
        if (!$ocr)
            return $this->failResourceGone('ocorrencia');
        $end = $this->endereco->find($ocr['id_endereco']);
        if (!$end)
            return $this->failResourceGone('usr');

        unset($ocr['id_endereco']);
        return $this->respond(array_merge($ocr, $end));
    }

    public function update($id)
    {
        if (!$this->request->hasHeader('Content-Type') || $this->request->header('Content-Type')->getValue() != 'application/json'){
            return $this->fail('Conteudo nao e json', 406);
        }

        $data = $this->request->getJSON(true);

        $ocr = $this->model->find($id);
        if (!$ocr)
            return $this->failResourceGone('ocorrencia');
        $end = $this->endereco->find($ocr['id_endereco']);
        if (!$end)
            return $this->failResourceGone('endereco');

        try{
            $this->endereco->update($end['id_endereco'],$data['endereco']);
            $this->model->update($id,$data['ocorrencia']);
        }catch(\Throwable $th){
            return $this->fail($th->getMessage());
        }

        unset($ocr['id_endereco']);
        return $this->respond(array_merge($ocr,$end), 200);
    }

    public function delete($id)
    {
        $ocr = $this->model->find($id);
        if (!$ocr)
            return $this->failResourceGone('ocorrencia');
        $end = $this->endereco->find($ocr['id_endereco']);
        if (!$end)
            return $this->failResourceGone('endereco');

        try {
            $this->model->delete($id);
            $this->endereco->delete($end['id_endereco']);
        } catch (\Throwable $th){
            return $this->fail($th->getMessage());
        }
        unset($ocr['id_endereco']);
        return $this->respond(array_merge($ocr,$end));
    }
}
