<?php

// PermissionMiddleware.php

namespace App\Middlewares;
use App\Models\Usuario;
use MF\Model\Container as ModelContainer;
use MF\Models\Container;

class PermissionMiddleware {

    public static function isAdmin() {
        $usuario = Usuario::checkLogin();
        return $usuario && $usuario->id == 1;
    }

    public static function checkIsAdmin() {
        if (!self::isAdmin()) {
            echo json_encode(["message" => "Você não é o admin master!", "ok" => false]);
            exit;
        }
    }

    public static function isEscritorio() {
        $usuario = Usuario::checkLogin();
        // if(!$usuario) {
        //     return false;
        // }
        // if($usuario->cnpj) {
        //     return true;
        // }
        return $usuario != false && isset($usuario->cnpj);
    }

    public static function checkIsEscritorio() {
        if (!self::isEscritorio()) {
            echo json_encode(["message" => "Você não é um escritório!", "ok" => false]);
            exit;
        }
    }

    public static function checkIsAdminOrEscritorio() {
        if (!self::isAdmin() && !self::isEscritorio()) {
            echo json_encode(["message" => "Você não é o admin master ou um escritório!", "ok" => false]);
            exit;
        }
    }

    public static function isUsuario() {
        return !self::isAdmin() && !self::isEscritorio();
    }

    public static function checkIsUsuario() {
        if (self::isEscritorio()) {
            echo json_encode(["message" => "Você não é um usuário comum!", "ok" => false]);
            exit;
        }
    }

    

    public static function checkConditions($conditions) {
        
        # Exemplo de uso para verificar se o id_escritorio do usuario logado é igual ao id_escritorio da obra
        # (ou qualquer outro dado da sua regra de negócio)
        // $conditions = [
        //     "id_escritorio" => $obra->id_escritorio
        // ];

        $usuario = Usuario::checkLogin();
        if(!$usuario) {
            return false;
        }
        if( $usuario->id == 1 ) {
            // Se for o admin, pode fazer tudo
            return true;
        }

        foreach ($conditions as $key => $value) {
            if($usuario->$key != $value) {
                echo json_encode(["message" => "Você não tem permissão! Não atende à condição necessária!", "ok" => false]);
                exit();
            }
        }

        return true;

    }

    public static function checkPermissions($action) {
        // Modelo de permissões: tabela de ações no sistema e tabela intermediária entre essas ações e usuários que definem as permissões
        $current_user = Usuario::checkLogin();

        // Se for master, pode fazer tudo
        if($current_user->id == 1) {
            return true;
        }

        if(!$current_user) {
            return false;
        }

        // Verificar se o usuário tem permissão para a ação.
        // Usar classe Model de Permissao para isso

        $permissao = ModelContainer::getModel("Permissao");
        $tem_permissao = $permissao->usuarioTemPermissao($current_user->id, $action);

        if(!$tem_permissao) {
            echo json_encode(["message" => "Você não tem permissão!", "ok" => false]);
            exit;
        }

        return true;

    }

}

# exemplo de uso para verificar se o id_escritorio do usuario logado é igual ao id_escritorio da obra 
# (ou qualquer outro dado da sua regra de negócio)
// $conditions = [
//     "id_escritorio" => $obra->id_escritorio
// ];
// PermissionMiddleware::checkConditions($conditions);

# Uma outra abordagem seria não dar um 'exit' no meio do código, mas retornar um booleano e tratar o retorno onde for chamado.
# Ou até mesmo criar outro método estático para isso, permitindo então parar todo o código e retornar o json ou tratar nos controllers.

?>