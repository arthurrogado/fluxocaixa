<?php

namespace App\Models;
use MF\Model\Model;

class Caixa extends Model
{

    public function abrirCaixa($nome, $observacoes, $id_escritorio, $id_usuario_abertura)
    {
        return $this->insert(
            "caixas",
            [
                "nome", "observacoes", "id_escritorio", "id_usuario_abertura"
            ],
            [
                $nome, $observacoes, $id_escritorio, $id_usuario_abertura
            ]
        );
    }

    public function getCaixas($id_escritorio)
    {
        return $this->select(
            "caixas",
            ["*"],
            "id_escritorio = $id_escritorio"
        );
    }

    public function visualizarCaixa($id)
    {
        return $this->selectOne(
            "caixas",
            ["*"],
            "id = $id"
        );
    }

    public function editarCaixa($id, $nome, $observacoes)
    {
        return $this->update(
            "caixas",
            ["nome", "observacoes"],
            [$nome, $observacoes],
            "id = $id"
        );
    }

    

}

?>