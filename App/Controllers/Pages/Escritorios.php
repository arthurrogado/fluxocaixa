<?php

namespace App\Controllers\Pages;
use MF\Controller\Action;
use App\Middlewares\PermissionMiddleware;

class Escritorios extends Action {

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
        PermissionMiddleware::checkIsAdminOrEscritorio();
        $this->render("visualizar");
    }

}

?>