<?php

namespace App\Models;
use MF\Model\Model;

class Operacao extends Model
{

    public function criarOperacao($nome, $observacoes, $valor, $id_caixa, $id_usuario, $data, $tipo, $id_carteira)
    {
        return $this->insert(
            "operacoes",
            [
                "nome", "observacoes", "valor", "id_caixa", "id_usuario", "data", "tipo", "id_carteira"
            ],
            [
                $nome, $observacoes, $valor, $id_caixa, $id_usuario, $data, $tipo, $id_carteira
            ]
        );
    
    }

    public function excluirOperacao($id)
    {
        return $this->update(
            "operacoes",
            ['excluido'],
            ['1'],
            "id = $id"
        );
    }

    public function editarOperacao($id, $nome, $valor, $observacoes, $data, $id_carteira)
    {
        return $this->update(
            "operacoes",
            ["nome", "valor", "observacoes", "data", "id_carteira"],
            [$nome, $valor, $observacoes, $data, $id_carteira],
            "id = $id"
        );
    }

    public function getOperacoesCaixa($id_caixa)
    {
        return $this->select(
            "operacoes",
            ["*"],
            "id_caixa = $id_caixa AND excluido='0' ORDER BY data DESC, data_criacao DESC"
        );
    }

    public function getOperacao($id)
    {
        return $this->selectOne(
            "operacoes",
            ["*"],
            "id = $id"
        );
    }

}

?>