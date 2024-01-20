<?php

namespace App\Controllers;
use App\Middlewares\PermissionMiddleware;
use App\Models\Caixa;
use App\Models\Usuario;

class CaixasController
{

    public function abrirCaixa()
    {

        // Permissões: 
        // - Usuário deve ter permissão para abrir caixa
        PermissionMiddleware::checkPermissions('abrirCaixa');

        // $usuario_atual = Container::getModel('Usuario')::checkLogin();
        $usuario_atual = Usuario::checkLogin();

        $nome = filter_input(INPUT_POST, 'nome');
        $observacoes = filter_input(INPUT_POST, 'observacoes');
        $id_escritorio = $usuario_atual->id_escritorio;
        $id_usuario_abertura = $usuario_atual->id;

        $caixa = new Caixa();
        $status = $caixa->abrirCaixa($nome, $observacoes, $id_escritorio, $id_usuario_abertura);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'message' => "Caixa aberto com sucesso"));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }

    }
    
    public function listarCaixas()
    {
        // Pegar todos os caixas abertos no escritório do usuário
        PermissionMiddleware::checkPermissions('listarCaixas');
        
        $usuario_atual = Usuario::checkLogin();
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
    
    public function visualizarCaixa()
    {
        // Permissões: 
        // - Usuário deve ter permissão para visualizar caixa
        // PermissionMiddleware::checkPermissions('visualizarCaixa');

        $id = filter_input(INPUT_POST, 'id');

        // $caixa = Container::getModel('Caixa');
        $caixa = new Caixa();
        $status = $caixa->visualizarCaixa($id);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'caixa' => $status['data']));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
        
    }
    
    public function excluirCaixa()
    {
        // Permissões: 
        // - Usuário deve ter permissão para excluir caixa
        // PermissionMiddleware::checkPermissions('excluirCaixa');

        $id = filter_input(INPUT_POST, 'id');

        // $caixa = Container::getModel('Caixa');
        $caixa = new Caixa();
        $status = $caixa->excluirCaixa($id);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'message' => "Caixa excluído com sucesso"));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

}

?>