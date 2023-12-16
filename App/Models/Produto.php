<?php

namespace App\Models;
use MF\Model\Model;

class Produto extends Model {

    public function getProdutos() {
        return $this->select(
            "tb_produtos",
            ['*']
        );
    }

}

?>