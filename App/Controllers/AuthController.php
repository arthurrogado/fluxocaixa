<?php

namespace App\Controllers;
use MF\Model\Container;

class AuthController {
    
    public function login() {
        $usuario = filter_input(INPUT_POST, "usuario", FILTER_DEFAULT);
        $senha = filter_input(INPUT_POST, "senha", FILTER_DEFAULT);
        
        $user = Container::getModel("Usuario");
        $status = $user->login($usuario, $senha);
        if($status) {
            echo json_encode(array('ok' => true));
        } else {
            echo json_encode(array('ok' => false, "message" => "Usuário ou senha incorretos" ));
        }
    }

    public function logout() {
        $user = Container::getModel("Usuario");
        if($user->logout()) {
            echo json_encode(array('ok' => true, "message" => "Logout feito com sucesso", "redirect" => "/login"));
        } else {
            echo json_encode(array('ok' => false, "message" => "Erro ao fazer logout (???)"));
        }
    }

    public function checkLogin()
    {
        // retornar dados do usuárioo logado (só não a senha)
        $user = Container::getModel("Usuario");
        $status = $user->checkLogin();

        if($status) {
            // Tirar o atributo "senha" do stdClass $status
            unset($status->senha);
            echo json_encode(array('ok' => true, "usuario" => $status));
        } else {
            echo json_encode(array('ok' => false, "status" => "Pelo jeito não está logado" ));
        }

    }

}

?>