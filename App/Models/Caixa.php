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
                "nome", "observacoes", "id_escritorio", "id_usuario_abertura"
            ],
            [
                $nome, $observacoes, $id_escritorio, $id_usuario_abertura
            ]
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
        return self::delete(
            "caixas",
            ['id' => $id]
        );
    }

}

?>