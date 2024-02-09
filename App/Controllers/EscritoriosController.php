<?php

namespace App\Controllers;
use App\Middlewares\PermissionMiddleware;
use App\Models\Escritorio;
use App\Models\Usuario;
use MF\Controller\MyAppException;

class EscritoriosController {

    public function criarEscritorio()
    {
        PermissionMiddleware::checkIsAdmin(); // Somente o ADMIN MASTER pode criar escritórios

        $nome = filter_input(INPUT_POST, "nome", FILTER_DEFAULT);
        $cnpj = filter_input(INPUT_POST, 'cnpj');
        $observacoes = filter_input(INPUT_POST, 'observacoes');
        
        $status = Escritorio::criarEscritorio($nome, $cnpj, $observacoes);
        if($status) {
            echo json_encode(array('ok' => true, 'message' => "Escritório criado com sucesso"));
        } else {
            new MyAppException("Erro ao criar escritório");
        }
    }

    public function getEscritorios()
    {
        
        if(PermissionMiddleware::isAdmin()){
            $escritorio = Escritorio::getEscritorios(); // Admin pega todos os escritórios
        } else if(PermissionMiddleware::isEscritorio()){
            $id_escritorio = Usuario::checkLogin()->id; // Pegar o ID do escritório logado
            $escritorio = Escritorio::getEscritorioInArray($id_escritorio); // Pegar em array pois será iterado no front
        } else {
            echo json_encode(array('ok' => false, 'message' => "Você não tem permissão para ver os escritórios. Faça login com o CNPJ do escritório para isso."));
            return;
        }

        if($escritorio) {
            echo json_encode(array('ok' => true, 'escritorios' => $escritorio));
        } else {
            new MyAppException("Erro ao buscar escritórios");
        }
    }

    public function visualizarEscritorio()
    {
        $id = filter_input(INPUT_POST, 'id');

        // Permissões: ser o admin ou ser o próprio escritório
        PermissionMiddleware::checkIsAdminOrEscritorio(); // Evita que usuários comuns vejam detalhes de escritório
        PermissionMiddleware::checkConditions(["id" => $id]); // Verifica se o usuário logado é o próprio escritório
        // Não tem perigo de checar o id de um usuário comum

        $escritorio = Escritorio::visualizarEscritorio($id);

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

        
        $status = Escritorio::editarEscritorio($id, $nome, $cnpj, $observacoes);
        if($status) {
            echo json_encode(array('ok' => true, 'message' => "Escritório editado com sucesso"));
        } else {
            throw new MyAppException("Erro ao editar escritório");
        }
    }

    public function excluirEscritorio()
    {
        // Somente o ADMIN MASTER pode excluir
        PermissionMiddleware::checkIsAdmin();

        $id = filter_input(INPUT_POST, 'id');
        $escritorio = new Escritorio();
        $status = $escritorio->excluirEscritorio($id);
        if($status) {
            echo json_encode(array('ok' => true, 'message' => "Escritório excluído com sucesso"));
        } else {
            throw new MyAppException("Erro ao excluir escritório");
        }
    }

}