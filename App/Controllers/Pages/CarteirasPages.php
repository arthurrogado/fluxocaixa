<?php

namespace App\Controllers\Pages;

use App\Middlewares\PermissionMiddleware;
use MF\Controller\Action;

class CarteirasPages extends Action
{

    public function listar()
    {
        PermissionMiddleware::checkIsEscritorio();
        $this->render('listar');
    }

}