<?php

namespace App\Models;
use MF\Model\Model;

class Permissao extends Model {

    public function getPermissao($id)
    {
        return self::selectOne(
            "permissoes",
            ["*"],
            ["id" => $id]
        );
    }

    public static function getAcoesPorControlador()
    {
        return self::select(
            "acoes_sistema",
            ["*"],
            [],
            "ORDER BY controlador"
        );
    }

    public static function getPermissoes()
    {
        return self::select(
            "permissoes",
            ["*"]
        );
    }

    public static function usuarioTemPermissao($id_usuario, $acao)
    {
        // return true;
        $status = self::select(
            "permissoes",
            ["*"],
            // "id_usuario = $id_usuario AND acao = '$acao'"
            ["id_usuario" => $id_usuario, "acao" => $acao]
        );
        return $status;
    }

}

?>