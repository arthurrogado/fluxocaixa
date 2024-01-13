<?php

namespace App\Controllers;
use MF\Model\Container;
use App\Middlewares\PermissionMiddleware;
use App\Models\Caixa;

class CaixasController
{

    public function abrirCaixa()
    {

        // Básico: id_escritorio é o mesmo do usuário logado

        // Permissões: 
        // - Usuário deve ter permissão para abrir caixa

        $usuario_atual = Container::getModel('Usuario')::checkLogin();

        $nome = filter_input(INPUT_POST, 'nome');
        $observacoes = filter_input(INPUT_POST, 'observacoes');
        $id_escritorio = $usuario_atual->id_escritorio;
        $id_usuario_abertura = $usuario_atual->id;

        $caixa = new Caixa();



    }

    public function listarCaixas()
    {
        // Pegar todos os caixas abertos no escritório do usuário
        PermissionMiddleware::checkPermissions('listarCaixas');

        $usuario_atual = Container::getModel('Usuario')::checkLogin();
        $id_escritorio = $usuario_atual->id_escritorio;

        // $caixa = Container::getModel('Caixa');
        $caixa = new Caixa();
        $status = $caixa->getCaixas($id_escritorio);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'caixas' => $status['data']));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }

    }

}

?>