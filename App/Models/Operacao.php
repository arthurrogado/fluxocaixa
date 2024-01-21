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

    public function getOperacoesCaixa($id_caixa)
    {
        return $this->select(
            "operacoes",
            ["*"],
            "id_caixa = $id_caixa ORDER BY data DESC, data_criacao DESC"
        );
    }

}

?>