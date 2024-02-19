<?php

namespace App\Controllers;
use App\Middlewares\PermissionMiddleware;
use App\Models\Caixa;
use App\Models\Operacao;
use App\Models\Usuario;
use MF\Controller\MyAppException;

class CaixasController
{

    public function abrirCaixa()
    {

        // Permissões: 
        // - Usuário deve ter permissão para abrir caixa
        PermissionMiddleware::checkPermission('abrirCaixa');

        // $usuario_atual = Container::getModel('Usuario')::checkLogin();
        $usuario_atual = Usuario::checkLogin();

        $nome = filter_input(INPUT_POST, 'nome');
        $observacoes = filter_input(INPUT_POST, 'observacoes');
        $id_escritorio = $usuario_atual->id_escritorio;
        $id_usuario_abertura = $usuario_atual->id;

        // $caixa = new Caixa();
        $status = Caixa::abrirCaixa($nome, $observacoes, $id_escritorio, $id_usuario_abertura);

        if($status) {
            echo json_encode(array('ok' => true, 'message' => "Caixa aberto com sucesso"));
        } else {
            throw new MyAppException("Erro ao abrir caixa.");
            // echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }

    }

    public function editarCaixa()
    {
        // Permissões: 
        // - Usuário deve ter permissão para editar caixa
        PermissionMiddleware::checkPermission('editarCaixa');

        $id = filter_input(INPUT_POST, 'id');

        $caixa = Caixa::visualizarCaixa($id);
        if(!$caixa) throw new MyAppException("Uai, não achei esse caixa! Tenta recomeçar.");
        
        PermissionMiddleware::checkConditions(["id_escritorio" => $caixa->id_escritorio]);

        $nome = filter_input(INPUT_POST, 'nome');
        $observacoes = filter_input(INPUT_POST, 'observacoes');

        $status = Caixa::editarCaixa($id, $nome, $observacoes);
        if($status) {
            echo json_encode(array('ok' => true, 'message' => "Caixa editado com sucesso"));
        } else {
            throw new MyAppException("Erro ao editar caixa.");
        }
    }
    
    public function listarCaixas()
    {
        // Pegar todos os caixas abertos no escritório do usuário
        PermissionMiddleware::checkPermission('listarCaixas', 'Você não tem permissão para listar caixas.');
        
        $usuario_atual = Usuario::checkLogin();
        $id_escritorio = $usuario_atual->id_escritorio;
        
        // $caixa = Container::getModel('Caixa');
        $status = Caixa::getCaixasAbertos($id_escritorio);
        if($status) {
            echo json_encode(array('ok' => true, 'caixas' => $status));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro ao listar caixas.", 'caixas' => $status));
            // throw new MyAppException("Não encontrei nenhum caixa.");
        }
        
    }
    
    public function visualizarCaixa()
    {
        // Permissões: 
        // - Usuário deve ter permissão para visualizar caixa
        PermissionMiddleware::checkPermission('visualizarCaixa');

        $id = filter_input(INPUT_POST, 'id');
        $caixa = new Caixa();

        // Verificar se o caixa (id) tem o mesmo escritório do usuário logado
        $caixa = Caixa::visualizarCaixa($id);
        if(!$caixa) throw new MyAppException("Erro ao visualizar.");
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
        PermissionMiddleware::checkPermission('excluirCaixa', 'Você não tem permissão para excluir caixa.');

        $id = filter_input(INPUT_POST, 'id');

        // $caixa = Container::getModel('Caixa');
        $caixa = new Caixa();
        $status = Caixa::excluirCaixa($id);
        if($status) {
            echo json_encode(array('ok' => true, 'message' => "Caixa excluído com sucesso"));
        } else {
            throw new MyAppException("Erro ao excluir caixa.");
            // echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

}

?>