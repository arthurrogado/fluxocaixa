<?php

namespace App\Models;
use MF\Model\Model;

class Caixa extends Model
{

    public static function abrirCaixa($nome, $observacoes, $id_escritorio, $id_usuario_abertura)
    {
        return self::insert(
            "caixas",
            [
                "nome" => $nome, 
                "observacoes" => $observacoes, 
                "id_escritorio" => $id_escritorio, 
                "id_usuario_abertura" => $id_usuario_abertura
            ]
            // [
            //     "nome", "observacoes", "id_escritorio", "id_usuario_abertura"
            // ],
            // [
            //     $nome, $observacoes, $id_escritorio, $id_usuario_abertura
            // ]
        );
    }

    public static function getCaixas($id_escritorio)
    {
        return self::select(
            "caixas",
            ["*"],
            ["id_escritorio" => $id_escritorio]
        );
    }

    public static function getCaixasAbertos($id_escritorio)
    {
        $sql = "SELECT * FROM caixas WHERE id_escritorio = :id_escritorio AND excluido = 0 AND (data_fechamento = '0000-00-00 00:00:00' OR data_fechamento IS NULL)";
        return self::executeSelect($sql, [":id_escritorio" => $id_escritorio]);
    }

    public static function visualizarCaixa($id)
    {
        return self::selectOne(
            "caixas",
            ["*"],
            ["id" => $id]
        );
    }

    public static function editarCaixa($id, $nome, $observacoes)
    {
        return self::update(
            "caixas",
            ["nome", "observacoes"],
            [$nome, $observacoes],
            "id = $id"
        );
    }

    public static function excluirCaixa($id)
    {
        return self::update(
            "caixas",
            ["excluido"],
            [1],
            "id = $id"
        );
    }

}