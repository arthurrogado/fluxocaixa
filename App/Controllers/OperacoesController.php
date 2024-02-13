<?php

namespace App\Controllers;
use App\Middlewares\PermissionMiddleware;
use App\Models\Caixa;
use App\Models\Carteira;
use App\Models\Operacao;
use App\Models\Usuario;
use MF\Controller\MyAppException;

class OperacoesController
{

    public function getPost($key)
    {
        return filter_input(INPUT_POST, $key);
    }

    public function getOperacoesCaixa() {

        // Permissões:
        // - Usuário deve ter permissão para visualizar operações do caixa
        PermissionMiddleware::checkPermissions("getOperacoesCaixa", "Você não tem permissão para visualizar operações do caixa.");

        $id_caixa = $this->getPost('id_caixa');

        // Verificar se o caixa (id_caixa) tem o mesmo escritório do usuário logado
        $caixa = Caixa::visualizarCaixa($id_caixa);
        if(!$caixa) throw new MyAppException("Erro: ao buscar caixa.");
        
        if($caixa->id_escritorio != Usuario::checkLogin()->id_escritorio) throw new MyAppException("Você não tem permissão para visualizar operações desse caixa. Você é de outro escritório.");

        $operacoes = Operacao::getOperacoesCaixa($id_caixa);
        // if(!$operacoes) throw new MyAppException("Operações não encontradas.");

        $soma_entradas = 0;
        $soma_saidas = 0;
        foreach($operacoes as $operacao) {
            // Verifica se a operação é de entrada ou saída
            if($operacao->tipo == 'e') {
                $soma_entradas += $operacao->valor;
            } else {
                $soma_saidas += $operacao->valor;
            }
            // Agrupa os valores de entrada para Dinheiro, Cartão, PIX, etc
        }

        echo json_encode(array('ok' => true, 'operacoes' => $operacoes, 'soma_entradas' => $soma_entradas, 'soma_saidas' => $soma_saidas));
    }

    public function verificarEntradaSaidaCarteira($id_carteira, $tipo_operacao) {
        // Verificar se a carteira permite entrada ou saída,
        // se permite entrada, verificar se o tipo de entrada é 1 (entrada)
        // se permite saída, verificar se o tipo de entrada é 0 (saída)
        // se não permite entrada nem saída, retornar erro
        // se permite entrada e saída, não verificar o tipo de entrada

        $carteira = Carteira::getCarteira($id_carteira);
        if(!$carteira) throw new MyAppException("Não encontrei a carteira de id '$id_carteira'.");

        if($carteira->entrada == '0' && $carteira->saida == '0') {
            echo json_encode(array('ok' => false, 'message' => "Essa carteira não permite entrada nem saída"));
            exit;
        } else if($carteira->entrada == '0' && $tipo_operacao == 'e') {
            echo json_encode(array('ok' => false, 'message' => "Essa carteira não permite entrada"));
            exit;
        } else if($carteira->saida == '0' && $tipo_operacao == 's') {
            echo json_encode(array('ok' => false, 'message' => "Essa carteira não permite saída"));
            exit;
        }
    }

    public function criarOperacao()
    {

        // Permissões: 
        // - Usuário deve ter permissão para criar operação
        PermissionMiddleware::checkPermissions('criarOperacao');

        $nome = $this->getPost('nome');
        $observacoes = $this->getPost('observacoes');
        $valor = $this->getPost('valor');
        $id_caixa = $this->getPost('id_caixa');
        $data = $this->getPost('data');
        $id_carteira = $this->getPost('id_carteira');
        $tipo = $this->getPost('tipo');
        $id_usuario = Usuario::checkLogin()->id;

        // Verificar se o caixa (id_caixa) tem o mesmo escritório do usuário logado
        $caixa = Caixa::visualizarCaixa($id_caixa);
        if(!$caixa) throw new MyAppException("Não encontrei o caixa de id '$id_caixa'.");
        PermissionMiddleware::checkConditions(["id_escritorio" => $caixa->id_escritorio], "Você não tem permissão para criar operações nesse caixa. Você é de outro escritório.");

        // Verifica se a carteira permite entrada ou saída
        $this->verificarEntradaSaidaCarteira($id_carteira, $tipo);

        // Verificar se a id_carteira pertence ao escritório do usuário logado ou se é universal (id_escritorio = NULL)
        $carteira = Carteira::getCarteira($id_carteira);
        if(!$carteira) throw new MyAppException("Não encontrei a carteira de id '$id_carteira'.");
        if($carteira->id_escritorio != Usuario::checkLogin()->id_escritorio && $carteira->id_escritorio != NULL) {
            throw new MyAppException("Você não tem permissão para criar operações nessa carteira. Você é de outro escritório ou essa carteira não é universal.");
        }

        $status = Operacao::criarOperacao($nome, $observacoes, $valor, $id_caixa, $id_usuario, $data, $tipo, $id_carteira);
        if($status) {
            echo json_encode(array('ok' => true, 'message' => "Operação criada com sucesso"));
        } else {
            throw new MyAppException("Erro ao criar operação.");
        }

    }

    public function editarOperacao()
    {
        // Permissões: 
        // - Usuário deve ter permissão para editar operação
        PermissionMiddleware::checkPermissions('editarOperacao');

        $id = $this->getPost('id');
        $tipo = $this->getPost('tipo');
        $nome = $this->getPost('nome');
        $observacoes = $this->getPost('observacoes');
        $valor = $this->getPost('valor');
        $data = $this->getPost('data');
        $id_carteira = $this->getPost('id_carteira');

        // Verificar se o caixa da operação (id) tem o mesmo escritório do usuário logado
        $operacao = Operacao::getOperacao($id);
        if(!$operacao) throw new MyAppException("Não achei essa operação de id '$id'.");
        $caixa = Caixa::visualizarCaixa($operacao->id_caixa);
        if(!$caixa) throw new MyAppException("Erro ao procurar pelo caixa da operação.");
        PermissionMiddleware::checkConditions(["id_escritorio" => $caixa->id_escritorio], "Você não tem permissão para editar operações desse caixa. Você é de outro escritório.");

        // Verificar se a carteira permite entrada ou saída do novo tipo da operação  
        $this->verificarEntradaSaidaCarteira($id_carteira, $tipo);
        
        $status = Operacao::editarOperacao($id, $nome, $valor, $observacoes, $data, $tipo, $id_carteira);
        if($status) {
            echo json_encode(array('ok' => true, 'message' => "Operação editada com sucesso"));
        } else {
            throw new MyAppException("Erro ao editar operação.");
        }
    }

    public function excluirOperacao()
    {
        // Permissões: 
        // - Usuário deve ter permissão para excluir operação
        PermissionMiddleware::checkPermissions('excluirOperacao');

        $id = $this->getPost('id');

        $status = Operacao::excluirOperacao($id);
        if($status) {
            echo json_encode(array('ok' => true, 'message' => "Operação excluída com sucesso"));
        } else {
            throw new MyAppException("Erro ao excluir operação.");
        }
    }

}

?>