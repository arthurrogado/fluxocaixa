<?php

namespace App\Controllers;
use App\Middlewares\PermissionMiddleware;
use App\Models\Permissao;
use MF\Controller\MyAppException;

class PermissoesController {

    public static function getAcoesPorControlador() {

        // Apenas admin ou escritório pode acessar as permissões
        PermissionMiddleware::checkIsAdminOrEscritorio();

        $acoes = Permissao::getAcoesPorControlador();
        if(!$acoes) throw new MyAppException("Não achei as ações do sistema! Contate o administrador desse sistema!");

        echo json_encode(array('ok' => true, 'acoes' => $acoes));
    }

}