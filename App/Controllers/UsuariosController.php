<?php

namespace App\Controllers;
use App\Middlewares\PermissionMiddleware;
use App\Models\Usuario;

class UsuariosController {

    public function criarUsuario()
    {

        // Exemplo de uso do middleware de permissão, que verifica se o usuário é o master (id = 1)
        PermissionMiddleware::checkConditions(["id" => 1]);

        $nome = filter_input(INPUT_POST, "nome", FILTER_DEFAULT);
        $usuario = filter_input(INPUT_POST, 'usuario');
        $senha = filter_input(INPUT_POST, 'senha');
        $cnpj_escritorio = filter_input(INPUT_POST, 'cnpj_escritorio');

        $user = new Usuario();
        
        // Verifica se o $usuario já existe
        if( $user->usuarioExiste($usuario) ) {
            echo json_encode(array('ok' => false, 'message' => "Usuário já existe"));
            return;
        }

        $status = $user->criarUsuario($nome, $usuario, $senha, $cnpj_escritorio);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'message' => "Usuário criado com sucesso"));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

    public function getUsuarios()
    {
        $user = new Usuario();
        $status = $user->getUsuarios();
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'usuarios' => $status['data']));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

    public function visualizarUsuario()
    {
        $id = filter_input(INPUT_POST, 'id');
        $user = new Usuario();
        $status = $user->visualizarUsuario($id);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'usuario' => $status['data']));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

    public function editarUsuario()
    {
        PermissionMiddleware::checkConditions(["id" => 1]);

        $id = filter_input(INPUT_POST, 'id');
        $nome = filter_input(INPUT_POST, "nome", FILTER_DEFAULT);
        $usuario = filter_input(INPUT_POST, 'usuario');
        $cnpj_escritorio = filter_input(INPUT_POST, 'cnpj_escritorio');

        if($id == 1) {
            echo json_encode(array('ok' => false, 'message' => "Não é possível editar o usuário master"));
            return;
        }

        if($usuario == "") {
            echo json_encode(array('ok' => false, 'message' => "Usuário não pode ser vazio"));
            return;
        }

        $user = new Usuario();
        $status = $user->editarUsuario($id, $nome, $usuario, $cnpj_escritorio);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'message' => "Usuário editado com sucesso"));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

    public function mudarSenhaUsuario()
    {
        PermissionMiddleware::checkConditions(["id" => 1]);

        $id = filter_input(INPUT_POST, 'id');
        $senha = filter_input(INPUT_POST, 'senha');

        $user = new Usuario();
        $status = $user->mudarSenhaUsuario($id, $senha);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'message' => "Senha alterada com sucesso"));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

    public function excluirUsuario()
    {
        PermissionMiddleware::checkConditions(["id" => 1]);

        $id = filter_input(INPUT_POST, 'id');

        if($id == 1) {
            echo json_encode(array('ok' => false, 'message' => "Não é possível excluir o usuário master"));
            return;
        }

        $user = new Usuario();
        $status = $user->excluirUsuario($id);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'message' => "Usuário excluído com sucesso"));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

}

?>