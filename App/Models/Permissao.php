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
            "metodos_sistema",
            ["*"],
            [],
            "ORDER BY controlador"
        );
    }

    public static function getMetodoDeAcao($id_metodo)
    {
        return self::selectOne(
            "metodos_sistema",
            ["metodo"],
            ["id" => $id_metodo]
        )->metodo ?? null;
    }

    public static function getIdMetodo($acao)
    {
        return self::selectOne(
            "metodos_sistema",
            ["id"],
            ["metodo" => $acao]
        )->id ?? null;
    }

    // public static function 

    public static function getPermissoes()
    {
        return self::select(
            "permissoes",
            ["*"]
        );
    }

    public static function usuarioTemPermissao($id_usuario, $id_metodo)
    {
        // return true;
        $status = self::select(
            "permissoes",
            ["*"],
            // "id_usuario = $id_usuario AND acao = '$acao'"
            ["id_usuario" => $id_usuario, "id_metodo" => $id_metodo]
        );
        return $status ? true : false;
    }

    public static function createPermissao($id_usuario, $id_metodo)
    {
        return self::insert(
            "permissoes",
            [
                "id_usuario" => $id_usuario,
                "id_metodo" => $id_metodo
            ]
            // ["id_usuario", "id_metodo"],
            // [$id_usuario, $id_metodo]
        );
    }

    public static function deletePermissao($id_usuario, $id_metodo)
    {
        return self::delete(
            "permissoes",
            ["id_usuario" => $id_usuario, "id_metodo" => $id_metodo]
        );
    }

    public static function deletePermissoesUsuario($id_usuario)
    {
        return self::delete(
            "permissoes",
            ["id_usuario" => $id_usuario]
        );
    }

}

?>