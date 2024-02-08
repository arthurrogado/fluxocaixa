<?php

namespace App\Controllers;
use App\Middlewares\PermissionMiddleware;
use App\Models\Escritorio;
use App\Models\Usuario;

class EscritoriosController {

    public function criarEscritorio()
    {
        PermissionMiddleware::checkIsAdmin();

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

    public function visualizarEscritorio()
    {
        $id = filter_input(INPUT_POST, 'id');

        // Permissões: ser o admin ou ser o próprio escritório
        PermissionMiddleware::checkIsAdminOrEscritorio(); // Evita que usuários comuns vejam detalhes de escritório
        PermissionMiddleware::checkConditions(["id" => $id]); // Verifica se o usuário logado é o próprio escritório
        // Não tem perigo de checar o id de um usuário comum

        $escritorio = new Escritorio();
        $escritorio = $escritorio->visualizarEscritorio($id);

        echo json_encode(array('ok' => true, 'escritorio' => $escritorio));

    }

    public function editarEscritorio()
    {
        // PermissionMiddleware::checkConditions(["id" => 1]);

        $id = filter_input(INPUT_POST, 'id');

        // Permissões: ser o admin ou ser o próprio escritório
        PermissionMiddleware::checkIsAdminOrEscritorio(); // Evita que usuários comuns vejam detalhes de escritório
        PermissionMiddleware::checkConditions(["id" => $id]); // Verifica se o usuário logado é o próprio escritório)
        // Não tem perigo de checar o id de um usuário comum

        // Se for o ADMIN, pode mudar o CNPJ, se for escritório, não pode
        if(PermissionMiddleware::isAdmin()) { 
            $cnpj = filter_input(INPUT_POST, 'cnpj');
        } else {
            $cnpj = Usuario::checkLogin()->cnpj;
        }


        $nome = filter_input(INPUT_POST, "nome", FILTER_DEFAULT);
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
        // Somente o ADMIN MASTER pode excluir
        PermissionMiddleware::checkIsAdmin();

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