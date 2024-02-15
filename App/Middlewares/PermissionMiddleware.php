<?php

// PermissionMiddleware.php

namespace App\Middlewares;

use App\Models\Permissao;
use App\Models\Usuario;
use MF\Controller\MyAppException;
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

    

    public static function checkConditions($conditions, $message = "Você não tem permissão! Não atende à condição necessária.") {
        
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
                echo json_encode(["message" => $message, "ok" => false]);
                exit();
            }
        }

        return true;

    }

    public static function newcheckPermissions()
    {
        // Pegar o controlador e o método que está sendo chamado
        // e verificar se o usuário tem permissão para acessar esse método
        
        // Se for master, pode fazer tudo
        if(self::isAdmin()) return true;

        $current_user = Usuario::checkLogin();
        if(!$current_user) return false;
        
        // Pegar o método que está sendo chamado
        $funcao = debug_backtrace()[1]['function'];
        // Pegar o nome do controlador
        $controller = debug_backtrace()[1]['class'];
        // var_dump($controller, $funcao);

        // Verificar se o usuário tem permissão para a ação.
        // Usar classe Model de Permissao para isso
        $id_metodo = Permissao::getIdMetodo($funcao);
        if(!$id_metodo) throw new \Exception("Não achei a ação '$funcao' no sistema.");
        
        if(!Permissao::usuarioTemPermissao($current_user->id, $id_metodo)) {
            throw new MyAppException("Você não tem permissão para a função '$funcao'.");
        }
    
    }

    public static function checkPermissions($metodo, $message = "Você não tem permissão!") {
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

        // $permissao = ModelContainer::getModel("Permissao");
        $id_metodo = Permissao::getIdMetodo($metodo);
        if(!$id_metodo) throw new \Exception("Não achei a ação '$metodo' no sistema.");
        
        $tem_permissao = Permissao::usuarioTemPermissao($current_user->id, $id_metodo);

        if(!$tem_permissao) {
            echo json_encode(["message" => $message, "ok" => false]);
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