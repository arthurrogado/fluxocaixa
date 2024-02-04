<?php

namespace App\Models;
use MF\Model\Model;

use function PHPSTORM_META\type;

class Usuario extends Model
{
    
    public function criarUsuario($nome, $usuario, $senha, $cnpj_escritorio)
    {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        return $this->insert(
            "usuarios",
            [
                "nome", "usuario", "senha", "cnpj_escritorio"
            ],
            [
                $nome, $usuario, $senha_hash, $cnpj_escritorio
            ]
        );
    }

    public function getUsuarios()
    {
        return $this->select(
            "usuarios",
            ["id", "nome", "usuario"]
        );
    }

    public function visualizarUsuario($id)
    {
        $status = $this->selectOne(
            "usuarios",
            ["*"],
            "id = $id"
        );
        if($status['data']) unset($status['data']->senha);
        return $status;
    }
        
    public function editarUsuario($id, $nome, $usuario, $cnpj_escritorio)
    {
        return $this->update(
            "usuarios",
            ["nome", "usuario", "cnpj_escritorio"],
            [$nome, $usuario, $cnpj_escritorio],
            "id = $id"
        );
    }

    public function mudarSenhaUsuario($id, $senha)
    {
        $senha = password_hash($senha, PASSWORD_DEFAULT);
        return $this->update(
            "usuarios",
            ["senha"],
            [$senha],
            "id = $id"
        );
    }

    public function excluirUsuario($id)
    {
        return $this->delete(
            "usuarios",
            "id = $id"
        );
    }

    public function usuarioExiste($usuario)
    {
        $status = self::selectOne(
            "usuarios",
            ["*"],
            "usuario = '$usuario'"
        );
        return $status['data'] != false;
    }

    public static function checkLogin() {
        // session_start();

        if(isset($_SESSION['usuario'])) {
            // return self::getUsuario($_SESSION['usuario']->id);
            return $_SESSION['usuario'];
        } else {
            if(isset($_SESSION['escritorio'])) {
                return $_SESSION['escritorio'];
            }
        }
        return false;
    }

    public static function login($username, $senha) {
        $query = "SELECT * FROM usuarios WHERE usuario = :usuario";
        $stmt = self::getConn()->prepare($query);
        $stmt->bindValue(":usuario", $username);
        $stmt->execute();
        $usuario = $stmt->fetch(\PDO::FETCH_OBJ);

        if($usuario) {
            if(password_verify($senha, $usuario->senha)) {
                // session_start();
                $_SESSION['usuario'] = $usuario;
                return true;
            } else {
                return false;
            }
        } else {
            // return false;
            // Tenta logar com o CNPJ do escritório
            $query = "SELECT * FROM escritorios WHERE cnpj = :usuario";
            $stmt = self::getConn()->prepare($query);
            $stmt->bindValue(":usuario", $username);
            $stmt->execute();
            $escritorio = $stmt->fetch(\PDO::FETCH_OBJ);

            if($escritorio) {
                if(password_verify($senha, $escritorio->senha)) {
                    // session_start();
                    $_SESSION['escritorio'] = $escritorio;
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public static function logout() {
        // session_start();
        return session_destroy();
    }

}

?>