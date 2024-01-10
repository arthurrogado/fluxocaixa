<?php

namespace App\Controllers;
use MF\Model\Container;

class PermissaoController {

    public function getPermissao()
    {
        $id = filter_input(INPUT_POST, 'id');
        $permissao = Container::getModel("Permissao");
        $status = $permissao->getPermissao($id);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'permissao' => $status['data']));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

    public function temPermissao($id_usuario)
    {
        $id_permissao = filter_input(INPUT_POST, 'id_permissao');
        $permissao = Container::getModel("Permissao");
        $status = $permissao->temPermissao($id_usuario, $id_permissao);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'tem_permissao' => $status['data']));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

}

?>