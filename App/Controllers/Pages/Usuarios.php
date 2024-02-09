<?php

namespace App\Controllers\Pages;
use MF\Controller\Action;
use App\Middlewares\PermissionMiddleware;

class Usuarios extends Action {

    public function listar() 
    {
        PermissionMiddleware::checkIsAdminOrEscritorio();
        $this->render("listar");
    }

    public function criar() 
    {
        PermissionMiddleware::checkIsAdminOrEscritorio();
        $this->render("criar");
    }

    public function visualizar()
    {
        // PermissionMiddleware::checkIsAdminOrEscritorio();
        $this->render("visualizar");
    }

}

?>