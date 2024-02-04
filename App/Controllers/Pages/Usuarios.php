<?php

namespace App\Controllers\Pages;
use MF\Controller\Action;
use App\Middlewares\PermissionMiddleware;

class Usuarios extends Action {

    public function listar() 
    {
        PermissionMiddleware::checkIsAdmin();
        $this->render("listar");
    }

    public function criar() 
    {
        PermissionMiddleware::checkIsAdmin();
        $this->render("criar");
    }

    public function visualizar()
    {
        PermissionMiddleware::checkIsAdmin();
        $this->render("visualizar");
    }

}

?>