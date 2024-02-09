<?php

namespace App\Models;
use MF\Model\Model;

class Escritorio extends Model {

    public function criarEscritorio($nome, $cnpj, $observacoes)
    {
        return $this->insert(
            "escritorios",
            [
                "nome", "cnpj", "observacoes"
            ],
            [
                $nome, $cnpj, $observacoes
            ]
        );
    }

    public function getEscritorios()
    {
        return $this->select(
            "escritorios",
            ["id", "nome", "cnpj"]
        );
    }

    public function visualizarEscritorio($id)
    {
        return $this->selectOne(
            "escritorios",
            ["*"],
            // "id = $id"
            ["id" => $id]
        );
    }

    public function editarEscritorio($id, $nome, $cnpj, $observacoes)
    {
        return $this->update(
            "escritorios",
            ["nome", "cnpj", "observacoes"],
            [$nome, $cnpj, $observacoes],
            "id = $id"
        );
    }

    public function excluirEscritorio($id)
    {
        return $this->delete(
            "escritorios",
            "id = $id"
        );
    }

    public function getEscritorioInArray($id)
    {
        return $this->select(
            "escritorios",
            ["*"],
            "id = $id"
        );
    }

    public static function getEscritorioByCnpj($cnpj)
    {
        return self::selectOne(
            "escritorios",
            ["*"],
            // "cnpj = '$cnpj'"
            ["cnpj" => $cnpj]
        );
    }

}

?>