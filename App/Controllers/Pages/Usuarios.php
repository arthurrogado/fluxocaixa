<?php

namespace App\Controllers\Pages;
use MF\Controller\Action;

class Usuarios extends Action {

    public function listar() 
    {
        $this->render("listar");
    }

    public function criar() 
    {
        $this->render("criar");
    }

    public function visualizar()
    {
        $this->render("visualizar");
    }

}

?>