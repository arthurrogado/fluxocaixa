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

}

?>