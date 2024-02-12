<?php

namespace App\Models;
use MF\Model\Model;

class Operacao extends Model
{

    public static function criarOperacao($nome, $observacoes, $valor, $id_caixa, $id_usuario, $data, $tipo, $id_carteira)
    {
        return self::insert(
            "operacoes",
            [
                "nome", "observacoes", "valor", "id_caixa", "id_usuario", "data", "tipo", "id_carteira"
            ],
            [
                $nome, $observacoes, $valor, $id_caixa, $id_usuario, $data, $tipo, $id_carteira
            ]
        );
    
    }

    public static function excluirOperacao($id)
    {
        return self::update(
            "operacoes",
            ['excluido'],
            ['1'],
            "id = $id"
        );
    }

    public static function editarOperacao($id, $nome, $valor, $observacoes, $data, $tipo, $id_carteira)
    {
        return self::update(
            "operacoes",
            ["nome", "valor", "observacoes", "data", "tipo", "id_carteira"],
            [$nome, $valor, $observacoes, $data, $tipo, $id_carteira],
            "id = $id"
        );
    }

    public static function getOperacoesCaixa($id_caixa)
    {
        return self::select(
            "operacoes",
            ["*"],
            // "id_caixa = $id_caixa AND excluido='0' ORDER BY data DESC, data_criacao DESC"
            ["id_caixa" => $id_caixa, "excluido" => '0'],
            'ORDER BY data DESC, data_criacao DESC'
        );
    }

    public static function getOperacao($id)
    {
        return self::selectOne(
            "operacoes",
            ["*"],
            ["id" => $id]
        );
    }

}

?>