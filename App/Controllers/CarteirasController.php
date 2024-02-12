<?php

namespace App\Controllers;
use App\Middlewares\PermissionMiddleware;
use App\Models\Caixa;
use App\Models\Carteira;
use App\Models\Usuario;
use MF\Controller\MyAppException;

class CarteirasController
{

    public function getCarteirasDeEscritorio()
    {
            
        // Permissões:
        // - Usuário deve ter permissão para visualizar carteiras do escritório

        $id_escritorio = filter_input(INPUT_POST, 'id_escritorio');

        // Verifica se é o id do escritório do usuário logado
        PermissionMiddleware::checkConditions(['id_escritorio' => $id_escritorio]);

        $carteiras = Carteira::getCarteiras($id_escritorio);
        if(!$carteiras) throw new MyAppException("Não encontrei nenhuma carteira.");

        echo json_encode(array('ok' => true, 'carteiras' => $carteiras));

    }

    public function getCarteirasDeCaixa()
    {

        // Permissões:
        // - Usuário deve ter permissão para visualizar carteiras do caixa

        $id_caixa = filter_input(INPUT_POST, 'id_caixa');

        // Verifica se o id_escritorio do caixa é o mesmo do usuário logado
        $caixa = Caixa::visualizarCaixa($id_caixa);
        if(!$caixa) throw new MyAppException("Não encontrei o caixa.");
        PermissionMiddleware::checkConditions(['id_escritorio' => $caixa->id_escritorio]);

        $classCarteira = new Carteira();
        $carteiras = $classCarteira->getCarteirasByCaixa($id_caixa);
        if(!$carteiras) throw new MyAppException("Não encontrei nenhuma carteira.");

        echo json_encode(array('ok' => true, 'carteiras' => $carteiras));

    }

}