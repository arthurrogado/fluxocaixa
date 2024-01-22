<?php

namespace App\Controllers;
use App\Middlewares\PermissionMiddleware;
use App\Models\Caixa;
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
            if($operacao->tipo_entrada == '1') {
                $soma_entradas += $operacao->valor;
            } else {
                $soma_saidas += $operacao->valor;
            }
            // Agrupa os valores de entrada para Dinheiro, Cartão, PIX, etc
        }
        echo json_encode(array('ok' => true, 'operacoes' => $operacoes, 'soma_entradas' => $soma_entradas, 'soma_saidas' => $soma_saidas));
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
        $id_forma_pagamento = $this->getPost('id_forma_pagamento');
        $tipo_entrada = $this->getPost('tipo_entrada');
        $id_usuario = Usuario::checkLogin()->id;

        $operacao = new Operacao();
        $status = $operacao->criarOperacao($nome, $observacoes, $valor, $id_caixa, $id_usuario, $data, $tipo_entrada, $id_forma_pagamento);
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
        $id_forma_pagamento = $this->getPost('id_forma_pagamento');

        $operacao = new Operacao();
        $status = $operacao->editarOperacao($id, $nome, $observacoes, $valor, $id_forma_pagamento);
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