<?php

namespace App\Models;
use MF\Model\Model;

class Permissao extends Model {

    public function getPermissao($id)
    {
        $this->select(
            "permissoes",
            ["*"],
            "id = $id"
        );
    }

    public function usuarioTemPermissao($id_usuario, $acao)
    {
        $status = $this->select(
            "permissoes",
            ["*"],
            "id_usuario = $id_usuario AND acao = '$acao'"
        );
        return $status['ok'];
    }

}

?>