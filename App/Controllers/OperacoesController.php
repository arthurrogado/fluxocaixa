<?php

namespace App\Controllers;
use App\Middlewares\PermissionMiddleware;
use App\Models\Caixa;
use App\Models\Carteira;
use App\Models\Operacao;
use App\Models\Usuario;

class OperacoesController
{

    public function getPost($key)
    {
        return filter_input(INPUT_POST, $key);
    }

    public function getOperacoesCaixa() {

        // Permissões:
        // - Usuário deve ter permissão para visualizar operações do caixa

        $id_caixa = $this->getPost('id_caixa');

        $classOperacao = new Operacao();
        $status = $classOperacao->getOperacoesCaixa($id_caixa);
        if(!$status['ok']) {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
            return;
        }

        $operacoes = $status['data'];
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
        $classCarteira = new Carteira();
        $status = $classCarteira->getCarteira($id_carteira);
        if(!$status['ok']) {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
            exit;
        }
        $carteira = $status['data'];
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

        // Verifica se a carteira permite entrada ou saída
        $this->verificarEntradaSaidaCarteira($id_carteira, $tipo);

        $operacao = new Operacao();
        $status = $operacao->criarOperacao($nome, $observacoes, $valor, $id_caixa, $id_usuario, $data, $tipo, $id_carteira);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'message' => "Operação criada com sucesso"));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }

    }

    public function editarOperacao()
    {
        // Permissões: 
        // - Usuário deve ter permissão para editar operação
        PermissionMiddleware::checkPermissions('editarOperacao');

        $id = $this->getPost('id');
        $nome = $this->getPost('nome');
        $observacoes = $this->getPost('observacoes');
        $valor = $this->getPost('valor');
        $data = $this->getPost('data');
        $id_carteira = $this->getPost('id_carteira');

        // Verificar se a nova carteira permite entrada ou saída
        $classOperacao = new Operacao();
        $status = $classOperacao->getOperacao($id);
        if(!$status['ok']) {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
            exit;
        }
        $operacao = $status['data'];
        $tipo = $operacao->tipo;
        $this->verificarEntradaSaidaCarteira($id_carteira, $tipo);
        

        $operacao = new Operacao();
        $status = $operacao->editarOperacao($id, $nome, $valor, $observacoes, $data, $id_carteira);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'message' => "Operação editada com sucesso"));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

    public function excluirOperacao()
    {
        // Permissões: 
        // - Usuário deve ter permissão para excluir operação
        PermissionMiddleware::checkPermissions('excluirOperacao');

        $id = $this->getPost('id');

        $operacao = new Operacao();
        $status = $operacao->excluirOperacao($id);
        if($status['ok']) {
            echo json_encode(array('ok' => true, 'message' => "Operação excluída com sucesso"));
        } else {
            echo json_encode(array('ok' => false, 'message' => "Erro: " . $status['message'] ));
        }
    }

}

?>