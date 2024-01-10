<?php

namespace App\Controllers;
use MF\Model\Container;
use App\Middlewares\PermissionMiddleware;

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
        $id_escritorio = $usuario_atual['id_escritorio'];
        $id_usuario_abertura = $usuario_atual['id'];

        var_dump($usuario_atual);

    }

    public function listarCaixas()
    {
        // Pegar todos os caixas abertos no escritório do usuário

        
    }

}

?>