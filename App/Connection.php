<?php

namespace App;
use App\Config\Secrets;
use MF\Controller\MyAppException;

class Connection {

    public static function getDB() {
        try{

            $conn = new \PDO(
                "mysql: host=".Secrets::$host."; dbname=".Secrets::$dbname,
                Secrets::$user,
                Secrets::$password
            );

            return $conn;

        } catch(\Throwable $th) {
            // echo $e->getMessage();
            // return null;
            // throw new \Exception("Erro ao conectar com o banco de dados.");
            throw new MyAppException("Erro ao conectar com o banco de dados.", $th);
        }
    }

}

?>