<?php

namespace App\Models;
use MF\Model\Model;

class Carteira extends Model
{

    // Anotações:
    // Basicamente a forma de recebimento ou de pagamento. 
    // Se o valor entrada estiver 1 (true), pode adicionar entradas nessa carteira.
    // Se o valor saída estiver 1 (true), pode retirar dessa carteira.

    // Exemplos:
    // - No escritório tem o caixa normal, de dinheiro físico (cédulas), então é dito que essa carteira permite saída e entrada, já que os pagamentos dos clientes em dinheiro vai para a mesma, e quando o escritório necessita de comprar algo pode usar essa carteira.
    // - Porém o escritório dispõe de uma maquininha de cartão de crédito/débito, que pode apenas receber. Logo essa carteira tem como true apenas o parâmetro entrada.
    // - E o mesmo escritório dispõe de um cartão de crédito, que neste caso só pode ter saídas, logo nessa carteira o atributo entrada é false (0).

    public static function getCarteiras()
    {
        return self::select(
            "carteiras",
            ["*"],
            ["ativa" => '1', "id_escritorio" => NULL]
        );
    }

    public static function getCarteirasDeEscritorio($id_escritorio)
    {
        return self::select(
            "carteiras",
            ["*"],
            // "(id_escritorio = $id_escritorio OR id_escritorio = NULL) AND ativa='1'"
            [
                "id_escritorio" => $id_escritorio,
                "ativa" => '1'
            ]
        );
    }

    public static function getCarteirasByCaixa($id_caixa)
    {
        // Pegar as carteiras que tem id_escritorio de acordo com o escritório do caixa
        // E pegar as carteiras que não tem id_escritorio

        $sql = "SELECT * FROM carteiras 
        WHERE (id_escritorio = (SELECT id_escritorio FROM caixas WHERE id = :id_caixa) OR id_escritorio IS NULL) 
        AND ativa='1'";
        return self::executeSelect($sql, array(':id_caixa' => $id_caixa));

    }

    public static function getCarteira($id)
    {
        return self::selectOne(
            "carteiras",
            ["*"],
            ["id" => $id]
        );
    }

    public static function criarCarteira($nome, $observacoes, $id_escritorio, $permite_entrada, $permite_saida)
    {
        return self::insert(
            "carteiras",
            ["nome" => $nome, "observacoes" => $observacoes, "id_escritorio" => $id_escritorio, "entrada" => $permite_entrada, "saida" => $permite_saida],
        );
    }

    public static function editarCarteira($id_carteira, $nome, $observacoes, $id_escritorio, $permite_entrada, $permite_saida, $ativa)
    {
        return self::update(
            "carteiras",
            ["nome", "observacoes", "id_escritorio", "entrada", "saida", "ativa"],
            [$nome, $observacoes, $id_escritorio, $permite_entrada, $permite_saida, $ativa],
            "id = $id_carteira"
        );
    }

}