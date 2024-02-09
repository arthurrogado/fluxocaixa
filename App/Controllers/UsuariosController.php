<?php

namespace App\Controllers;

use App\Middlewares\PermissionMiddleware;
use App\Models\Escritorio;
use App\Models\Usuario;
use MF\Controller\MyAppException;

class UsuariosController
{

    public function criarUsuario()
    {

        PermissionMiddleware::checkIsAdminOrEscritorio();

        $nome = filter_input(INPUT_POST, "nome", FILTER_DEFAULT);
        $usuario = filter_input(INPUT_POST, 'usuario');
        $senha = filter_input(INPUT_POST, 'senha');
        
        // Se o usuário for um escritório, ele não pode mudar o escritorio do usuario
        if (PermissionMiddleware::isEscritorio()) {
            $cnpj_escritorio = Usuario::checkLogin()->cnpj;
        } else {
            $cnpj_escritorio = filter_input(INPUT_POST, 'cnpj_escritorio');
        }

        $escritorio = Escritorio::getEscritorioByCnpj($cnpj_escritorio);
        if(!$escritorio) new MyAppException("Erro ao criar usuário: escritório não encontrado");

        // Verifica se o $usuario já existe
        if (Usuario::usuarioExiste($usuario)) new MyAppException("Usuário '$usuario' já existe");

        $status = Usuario::criarUsuario($nome, $usuario, password_hash($senha, PASSWORD_DEFAULT), $escritorio->id);
        if (!$status) {
            // echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message']));
            new MyAppException("Erro ao criar usuário: ");
        } else {
            echo json_encode(array('ok' => true, 'message' => "Usuário criado com sucesso"));
        }
    }

    public function getUsuarios()
    {
        $user = new Usuario();
        if (PermissionMiddleware::isAdmin()) {
            // Se for admin, pode ver todos os usuários
            $status = $user->getUsuarios();
        } else if (PermissionMiddleware::isEscritorio()) {
            // Se não for admin, só pode ver os usuários se for um escritório
            $id_escritorio = Usuario::checkLogin()->id; // Pegar o ID do escritório logado
            $status = $user->getUsuariosFromEscritorio($id_escritorio);
        } else {
            echo json_encode(array('ok' => false, 'message' => "Você não tem permissão para ver os usuários. Faça login com o CNPJ do escritório para isso."));
            return;
        }

        if ($status['ok']) {
            echo json_encode(array('ok' => true, 'usuarios' => $status['data']));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message']));
        }
    }

    public static function manipulateStatus($status)
    {
        if ($status['ok']) {
            return $status['data'];
        } else {
            throw new MyAppException("Error: " . $status['message']);
        }
    }

    public function visualizarUsuario()
    {
        $id = filter_input(INPUT_POST, 'id');
        $user = new Usuario();
        $usuario = $user->visualizarUsuario($id);
        
        // Permissões: ser o admin ou ser o próprio usuário ou ser o escritório do usuário
        if(PermissionMiddleware::IsAdmin()) {
            // Se for admin, pode ver todos os usuários
        } else
        
        if(PermissionMiddleware::isEscritorio()) {
            // Verificar se o usuário é do escritório
            if($usuario->id_escritorio != Usuario::checkLogin()->id) {
                echo json_encode(array('ok' => false, 'message' => "Você não tem permissão para ver esse usuário!"));
                return;
            }
        } else 
        
        if( PermissionMiddleware::checkConditions(["id" => $id]) ) {
            // Se for o próprio usuário, verifique se é ele mesmo
        }
        
        else {
            echo json_encode(array('ok' => false, 'message' => "Você não tem permissão para ver esse usuário"));
            return;
        }

        // if(!$status['ok']) throw new MyAppException("Erro ao visualizar usuário: " . $status['message']);
        // $usuario = $status['data'];

        if ($usuario) unset($usuario->senha);
        // if($status['data']) unset($status['data']->senha);

        // Pegar o cnpj do escritório do usuário
        $escritorio = $user->selectOne(
            "escritorios",
            ["cnpj"],
            // "id = " . $usuario->id_escritorio
            ["id" => $usuario->id_escritorio]
        );

        $usuario->cnpj_escritorio = $escritorio->cnpj;
        // $status['data']->cnpj_escritorio = $escritorio['data']->cnpj;

        echo json_encode(array('ok' => true, 'usuario' => $usuario));
            
    }

    public function editarUsuario()
    {
        // PermissionMiddleware::checkConditions(["id" => 1]);
        // PermissionMiddleware::checkIsAdmin();

        $id = filter_input(INPUT_POST, 'id');
        $nome = filter_input(INPUT_POST, "nome", FILTER_DEFAULT);
        $usuario = filter_input(INPUT_POST, 'usuario');

        // Se o usuário for um escritório, ele não pode mudar o escritorio do usuario
        if (PermissionMiddleware::isEscritorio()) {
            $cnpj_escritorio = null;
        } else {
            $cnpj_escritorio = filter_input(INPUT_POST, 'cnpj_escritorio');
        }

        // Verificações | regras de negócio
        $exceptions = [
            $id == 1 => "Não é possível editar o usuário master",
            $usuario == "" => "Usuário não pode ser vazio",
        ];
        foreach ($exceptions as $key => $value) {
            if ($key) {
                echo json_encode(array('ok' => false, 'message' => $value));
                return;
            }
        }

        $user = new Usuario();
        if ($cnpj_escritorio) {
            $status = $user->editarUsuarioCnpj($id, $nome, $usuario, $cnpj_escritorio);
        } else {
            $status = $user->editarUsuario($id, $nome, $usuario);
        }

        if ($status) {
            echo json_encode(array('ok' => true, 'message' => "Usuário editado com sucesso"));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message']));
        }
    }

    public function mudarSenhaUsuario()
    {
        PermissionMiddleware::checkConditions(["id" => 1]);

        $id = filter_input(INPUT_POST, 'id');
        $senha = filter_input(INPUT_POST, 'senha');

        $user = new Usuario();
        $status = $user->mudarSenhaUsuario($id, $senha);
        if ($status['ok']) {
            echo json_encode(array('ok' => true, 'message' => "Senha alterada com sucesso"));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message']));
        }
    }

    public function excluirUsuario()
    {
        PermissionMiddleware::checkConditions(["id" => 1]);

        $id = filter_input(INPUT_POST, 'id');

        if ($id == 1) {
            echo json_encode(array('ok' => false, 'message' => "Não é possível excluir o usuário master"));
            return;
        }

        $user = new Usuario();
        $status = $user->excluirUsuario($id);
        if ($status['ok']) {
            echo json_encode(array('ok' => true, 'message' => "Usuário excluído com sucesso"));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message']));
        }
    }

}

?>