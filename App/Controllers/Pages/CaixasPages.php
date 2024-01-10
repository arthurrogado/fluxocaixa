<?php

namespace App\Controllers\Pages;
use MF\Controller\Action;

class CaixasPages extends Action {

    public function listar()
    {
        $this->render('listar');
    }

}

?>