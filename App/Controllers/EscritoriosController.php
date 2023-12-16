<?php

namespace App\Controllers;
use MF\Model\Container;
use App\Middlewares\PermissionMiddleware;

class EscritoriosController {

    public function criarEscritorio()
    {
        PermissionMiddleware::checkConditions(["id" => 1]);

        $nome = filter_input(INPUT_POST, "nome", FILTER_DEFAULT);
        $cnpj = filter_input(INPUT_POST, 'cnpj');
        $observacoes = filter_input(INPUT_POST, 'observacoes');

        $escritorio = Container::getModel("Escritorio");
        
        $status = $escritorio->criarEscritorio($nome, $cnpj, $observacoes);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'message' => "Escritório criado com sucesso"));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

    public function getEscritorios()
    {
        $escritorio = Container::getModel("Escritorio");
        $status = $escritorio->getEscritorios();
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'escritorios' => $status['data']));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

    public function getEscritorio($id)
    {
        $escritorio = Container::getModel("Escritorio");
        $status = $escritorio->getEscritorio($id);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'escritorio' => $status['data']));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

    public function visualizarEscritorio()
    {
        $id = filter_input(INPUT_POST, 'id');
        $escritorio = Container::getModel("Escritorio");
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

        $escritorio = Container::getModel("Escritorio");
        
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
        $escritorio = Container::getModel("Escritorio");
        $status = $escritorio->excluirEscritorio($id);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'message' => "Escritório excluído com sucesso"));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

}

?>