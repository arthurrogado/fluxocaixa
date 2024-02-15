<?php

namespace App\Controllers;
use App\Middlewares\PermissionMiddleware;
use App\Models\Caixa;
use App\Models\Carteira;
use App\Models\Usuario;
use MF\Controller\MyAppException;
use MF\Controller\Controller;

class CarteirasController extends Controller
{

    public function getCarteirasDeEscritorio()
    {
            
        // Permissões:
        // - Usuário deve ter permissão para visualizar carteiras do escritório

        if(PermissionMiddleware::isEscritorio()) {
            $id_escritorio = Usuario::checkLogin()->id;
        } else if(PermissionMiddleware::isUsuario()) {
            $id_escritorio = filter_input(INPUT_POST, 'id_escritorio');
            // Verifica se é o id do escritório do usuário logado
            PermissionMiddleware::checkConditions(['id_escritorio' => $id_escritorio]);
        }

        $carteiras_padrao = Carteira::getCarteiras();
        // if(!$carteiras_padrao) throw new MyAppException("Não encontrei nenhuma carteira.");

        $carteiras_escritorio = Carteira::getCarteirasDeEscritorio($id_escritorio);
        // if(!$carteiras_escritorio) throw new MyAppException("Não encontrei nenhuma carteira.");

        $carteiras = array_merge($carteiras_padrao, $carteiras_escritorio);

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

    public function criarCarteira()
    {

        // Permissões
        // - Usuário deve ser um escritório
        PermissionMiddleware::checkIsEscritorio();

        $nome = filter_input(INPUT_POST, 'nome');
        $observacoes = filter_input(INPUT_POST, 'observacoes');
        $id_escritorio = Usuario::checkLogin()->id;
        $permite_entrada = filter_input(INPUT_POST, 'permite_entrada');
        $permite_saida = filter_input(INPUT_POST, 'permite_saida');

        if(!$nome || !$id_escritorio || $permite_entrada || !$permite_saida) throw new MyAppException("Preencha todos os campos.");

        $status = Carteira::criarCarteira($nome, $observacoes, $id_escritorio, $permite_entrada, $permite_saida);
        if(!$status) throw new MyAppException("Erro ao criar carteira.");
        echo json_encode(array('ok' => true, 'message' => 'Carteira criada com sucesso!'));

    }

    public function editarCarteira()
    {

        // Permissões
        // - Usuário deve ser um escritório
        PermissionMiddleware::checkIsEscritorio();

        $id_carteira = self::getPost('id_carteira');
        $nome = self::getPost('nome');
        $observacoes = self::getPost('observacoes');
        $id_escritorio = Usuario::checkLogin()->id;
        $permite_entrada = self::getPost('permite_entrada');
        $permite_saida = self::getPost('permite_saida');
        $ativa = self::getPost('ativa');

        if(!$id_carteira || !$nome || !$id_escritorio || $permite_entrada || !$permite_saida || $ativa) throw new MyAppException("Preencha todos os campos.");

        $status = Carteira::editarCarteira($id_carteira, $nome, $observacoes, $id_escritorio, $permite_entrada, $permite_saida, $ativa);
        if(!$status) throw new MyAppException("Erro ao editar carteira.");
        echo json_encode(array('ok' => true, 'message' => 'Carteira editada com sucesso!'));

    }

}