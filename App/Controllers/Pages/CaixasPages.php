<?php

namespace App\Controllers\Pages;

use App\Middlewares\PermissionMiddleware;
use MF\Controller\Action;

class CaixasPages extends Action {

    public function listar()
    {
        // Para poder listar os caixas, é necessário que NÃO seja login com escritório/CNPJ (ou seja, que seja um usuário comum)
        PermissionMiddleware::checkIsUsuario();
        PermissionMiddleware::checkPermission("listarCaixas", "Você não tem permissão para listar caixas.");
        $this->render('listar');
    }

    public function visualizar()
    {
        // Para poder visualizar um caixa, é necessário que não seja login com escritório/CNPJ (ou seja, que seja um usuário comum que tenha permissao para visualizar caixa)
        PermissionMiddleware::checkPermission("visualizarCaixa", "Você não tem permissão para visualizar caixa.");
        $this->render('visualizar');
    }

}