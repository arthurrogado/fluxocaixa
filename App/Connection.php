<?php

namespace App;
use App\Config\Secrets;

class Connection {

    public static function getDB() {
        try{

            $conn = new \PDO(
                "mysql: host=".Secrets::$host."; dbname=".Secrets::$dbname,
                Secrets::$user,
                Secrets::$password
            );

            return $conn;

        } catch(\PDOException $e) {
            echo $e->getMessage();
        }
    }

}

?>