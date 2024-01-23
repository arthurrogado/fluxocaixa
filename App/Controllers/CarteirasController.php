<?php

namespace App\Controllers;
use App\Middlewares\PermissionMiddleware;
use App\Models\Caixa;
use App\Models\Carteira;
use App\Models\Usuario;

class CarteirasController
{

    public function getCarteirasDeEscritorio()
    {
            
        // Permissões:
        // - Usuário deve ter permissão para visualizar carteiras do escritório

        $id_escritorio = filter_input(INPUT_POST, 'id_escritorio');

        // Verifica se é o id do escritório do usuário logado
        PermissionMiddleware::checkConditions(['id_escritorio' => $id_escritorio]);

        $classCarteira = new Carteira();
        $status = $classCarteira->getCarteiras($id_escritorio);
        if(!$status['ok']) {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
            return;
        }

        $carteiras = $status['data'];
        echo json_encode(array('ok' => true, 'carteiras' => $carteiras));

    }

    public function getCarteirasDeCaixa()
    {

        // Permissões:
        // - Usuário deve ter permissão para visualizar carteiras do caixa

        $id_caixa = filter_input(INPUT_POST, 'id_caixa');

        // Verifica se o id_escritorio do caixa é o mesmo do usuário logado
        $classCaixa = new Caixa();
        $status = $classCaixa->visualizarCaixa($id_caixa);
        if(!$status['ok']) {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
            return;
        }
        $caixa = $status['data'];
        PermissionMiddleware::checkConditions(['id_escritorio' => $caixa->id_escritorio]);

        $classCarteira = new Carteira();
        $status = $classCarteira->getCarteirasByCaixa($id_caixa);
        if(!$status['ok']) {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
            return;
        }

        $carteiras = $status['data'];
        echo json_encode(array('ok' => true, 'carteiras' => $carteiras));

    }

}