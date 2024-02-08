<?php

namespace App\Controllers;
use App\Middlewares\PermissionMiddleware;
use App\Models\Caixa;
use App\Models\Operacao;
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

    public function editarCaixa()
    {
        // Permissões: 
        // - Usuário deve ter permissão para editar caixa
        PermissionMiddleware::checkPermissions('editarCaixa');

        $id = filter_input(INPUT_POST, 'id');

        $class_caixa = new Caixa();
        $status = $class_caixa->visualizarCaixa($id);
        if(!$status['ok']) {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
            exit;
        }
        $caixa = $status['data'];
        
        PermissionMiddleware::checkConditions(["id_escritorio" => $caixa->id_escritorio]);

        $nome = filter_input(INPUT_POST, 'nome');
        $observacoes = filter_input(INPUT_POST, 'observacoes');

        $caixa = new Caixa();
        $status = $caixa->editarCaixa($id, $nome, $observacoes);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'message' => "Caixa editado com sucesso"));
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
        PermissionMiddleware::checkPermissions('visualizarCaixa');

        $id = filter_input(INPUT_POST, 'id');
        $caixa = new Caixa();

        // Verificar se o caixa (id) tem o mesmo escritório do usuário logado
        $status = $caixa->visualizarCaixa($id);
        if(!$status['ok']) {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
            exit;
        }
        $caixa = $status['data'];
        PermissionMiddleware::checkConditions(["id_escritorio" => $caixa->id_escritorio]);

        $sql = "SELECT 
            c.*, 
            ua.nome AS nome_usuario_abertura,
            uf.nome AS nome_usuario_fechamento
            FROM caixas AS c
        JOIN usuarios AS ua
            ON ua.id = c.id_usuario_abertura
        LEFT JOIN usuarios AS uf
            ON uf.id = c.id_usuario_fechamento
        WHERE c.id = :id
        ";

        $conn = Caixa::getConn();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();        
        $caixa = $stmt->fetch(\PDO::FETCH_OBJ);

        echo json_encode(array('ok' => true, 'caixa' => $caixa));
        
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