<?php

namespace App\Models;
use MF\Model\Model;

class Escritorio extends Model {

    public static function criarEscritorio($nome, $cnpj, $observacoes)
    {
        return self::insert(
            "escritorios",
            [
                "nome", "cnpj", "observacoes"
            ],
            [
                $nome, $cnpj, $observacoes
            ]
        );
    }

    public static function getEscritorios()
    {
        return self::select(
            "escritorios",
            ["id", "nome", "cnpj"]
        );
    }

    public static function visualizarEscritorio($id)
    {
        return self::selectOne(
            "escritorios",
            ["*"],
            ["id" => $id]
        );
    }

    public static function existeCnpj($cnpj)
    {
        return self::selectOne(
            "escritorios",
            ["*"],
            ["cnpj" => $cnpj]
        ) != false;
    }

    public static function editarEscritorio($id, $nome, $cnpj, $observacoes)
    {
        return self::update(
            "escritorios",
            ["nome", "cnpj", "observacoes"],
            [$nome, $cnpj, $observacoes],
            "id = $id"
        );
    }

    public static function excluirEscritorio($id)
    {
        return self::delete(
            "escritorios",
            ["id" => $id]
        );
    }

    public static function getEscritorioInArray($id)
    {
        return self::select(
            "escritorios",
            ["*"],
            ["id" => $id]
        );
    }

    public static function getEscritorioByCnpj($cnpj)
    {
        return self::selectOne(
            "escritorios",
            ["*"],
            ["cnpj" => $cnpj]
        );
    }

}