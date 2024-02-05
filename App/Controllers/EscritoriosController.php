<?php

namespace App\Controllers;
use App\Middlewares\PermissionMiddleware;
use App\Models\Escritorio;
use App\Models\Usuario;

class EscritoriosController {

    public function criarEscritorio()
    {
        PermissionMiddleware::checkConditions(["id" => 1]);

        $nome = filter_input(INPUT_POST, "nome", FILTER_DEFAULT);
        $cnpj = filter_input(INPUT_POST, 'cnpj');
        $observacoes = filter_input(INPUT_POST, 'observacoes');

        $escritorio = new Escritorio();
        
        $status = $escritorio->criarEscritorio($nome, $cnpj, $observacoes);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'message' => "Escritório criado com sucesso"));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

    public function getEscritorios()
    {
        
        $escritorio = new Escritorio();
        if(PermissionMiddleware::isAdmin()){
            $status = $escritorio->getEscritorios();
        } else if(PermissionMiddleware::isEscritorio()){
            $id_escritorio = Usuario::checkLogin()->id; // Pegar o ID do escritório logado
            $status = $escritorio->getEscritorioInArray($id_escritorio);
        } else {
            echo json_encode(array('ok' => false, 'message' => "Você não tem permissão para ver os escritórios. Faça login com o CNPJ do escritório para isso."));
            return;
        }

        if($status['ok']) {
            echo json_encode(array('ok' => true, 'escritorios' => $status['data']));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

    public function getEscritorio($id)
    {
        $escritorio = new Escritorio();
        $status = $escritorio->visualizarEscritorio($id);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'escritorio' => $status['data']));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

    public function visualizarEscritorio()
    {
        $id = filter_input(INPUT_POST, 'id');
        $escritorio = new Escritorio();
        $status = $escritorio->visualizarEscritorio($id);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'escritorio' => $status['data']));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

    public function editarEscritorio()
    {
        PermissionMiddleware::checkConditions(["id" => 1]);

        $id = filter_input(INPUT_POST, 'id');
        $nome = filter_input(INPUT_POST, "nome", FILTER_DEFAULT);
        $cnpj = filter_input(INPUT_POST, 'cnpj');
        $observacoes = filter_input(INPUT_POST, 'observacoes');

        $escritorio = new Escritorio();
        
        $status = $escritorio->editarEscritorio($id, $nome, $cnpj, $observacoes);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'message' => "Escritório editado com sucesso"));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

    public function excluirEscritorio()
    {
        PermissionMiddleware::checkConditions(["id" => 1]);

        $id = filter_input(INPUT_POST, 'id');
        $escritorio = new Escritorio();
        $status = $escritorio->excluirEscritorio($id);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'message' => "Escritório excluído com sucesso"));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

}

?>