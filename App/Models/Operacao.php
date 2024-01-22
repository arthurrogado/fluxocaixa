<?php

namespace App\Models;
use MF\Model\Model;

class Operacao extends Model
{

    public function criarOperacao($nome, $observacoes, $valor, $id_caixa, $id_usuario, $data, $tipo_entrada, $id_forma_pagamento)
    {
        return $this->insert(
            "operacoes",
            [
                "nome", "observacoes", "valor", "id_caixa", "id_usuario", "data", "tipo_entrada", "id_forma_pagamento"
            ],
            [
                $nome, $observacoes, $valor, $id_caixa, $id_usuario, $data, $tipo_entrada, $id_forma_pagamento
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

    public function editarOperacao($id, $nome, $observacoes, $valor, $id_forma_pagamento)
    {
        return $this->update(
            "operacoes",
            ["nome", "observacoes", "valor", "id_forma_pagamento"],
            [$nome, $observacoes, $valor, $id_forma_pagamento],
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

}

?>