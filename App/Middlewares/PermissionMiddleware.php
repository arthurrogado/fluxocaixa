<?php

// PermissionMiddleware.php

namespace App\Middlewares;
use App\Models\Usuario;

class PermissionMiddleware {

    public static function checkConditions($conditions) {
        
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
                echo json_encode(["message" => "Você não tem permissão!", "ok" => false]);
                exit;
            }
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