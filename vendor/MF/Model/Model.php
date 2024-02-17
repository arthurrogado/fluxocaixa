<?php

namespace MF\Model;
// use App\Config\Config;
use App\Config\Config;
use App\Connection;
use MF\Controller\MyAppException;
use Throwable;

abstract class Model {

    protected static $conn;

    // public function __construct(\PDO $db) {
    //     $this->db = $db;
    // }

    public function __construct() {
        // Instanciar a conexão com o banco de dados
        $this->getConn();
    }

    public function __get($attr) {
        return $this->$attr;
    }

    public function __set($attr, $value) {
        $this->$attr = $value;
    }

    public static function getConn() {
        if (!isset(self::$conn)) {
            self::$conn = Connection::getDB();
            return self::$conn;
        } else {
            return self::$conn;
        }
    }

    // Métodos genéricos para CRUD

    public static function insert(string $table, array $data) : int {
        try {
            self::getConn(); // Usar esse método estático para que a conexão seja feita, já que o método é estático (não depende de instância da classe)

            $columns = array_keys($data);
            $values = array_values($data);

            $preparedValues = array_map(function($value) {
                return ($value !== null) ? "'$value'" : 'NULL';
            }, $values);

            $query = "INSERT INTO $table (".implode(", ", $columns).") VALUES (". implode(", ", $preparedValues). ")";
            // like: "INSERT INTO users (name, email) VALUES ('Durov', 'durov@telegram')";

            $stmt = self::$conn->prepare($query);
            $result = $stmt->execute();
            // retonar o id do registro inserido
            $id = self::$conn->lastInsertId();
            // return (["ok" => $result, "id" => $id]);
            return $id ? $id : $result;
        } catch (\Throwable $th) {
            // return (["ok" => false, "message" => $th->getMessage(), "line" => $th->getLine()]);
            throw new MyAppException("Erro ao inserir registro: ", $th);
        }
    }
    

    public static function select(string $table, array $columns, array $where = [], string $finals = '') : array {
        try {
            self::getConn();
    
            $query = "SELECT ".implode(", ", $columns)." FROM $table";
            
            // Exemplo de um where composto:
            // $where = [
            //     "id" => 1,
            //     "OR" => [
            //         "nome" => "João",
            //         "idade" => 25
            //     ]
            // ];

            if (!empty($where)) {
                $query .= " WHERE ";
    
                $conditions = [];
                foreach ($where as $key => $value) {
                    // Verifica se é uma condição composta com operadores lógicos
                    if (is_array($value)) { // se for um array, é uma condição composta
                        $subconditions = []; // array para armazenar as condições compostas
                        foreach ($value as $subkey => $subvalue) {
                            $subconditions[] = "$subkey = :$subkey"; // popula o array com as subcondições
                        }
                        $conditions[] = "(" . implode(" OR ", $subconditions) . ")"; // 

                    } else { // se não for um array, é uma condição simples
                        $conditions[] = "$key = :$key";
                    }
                }
    
                $query .= implode(" AND ", $conditions);
            }

            $query .= " $finals"; // Use finais para adicionar ORDER BY, LIMIT, etc.
    
            $stmt = self::$conn->prepare($query);
            
            // Usar bindValue no where para evitar SQL injection
            foreach ($where as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $subkey => $subvalue) {
                        $stmt->bindValue(":$subkey", $subvalue);
                    }
                } else {
                    $stmt->bindValue(":$key", $value);
                }
            }
    
            $stmt->execute();
    
            $result = $stmt->fetchAll(\PDO::FETCH_OBJ);
    
            return $result;
        } catch (\Throwable $th) {
            throw new MyAppException("Erro ao selecionar registro:", $th);
        }
    }
    

    public static function selectOne(string $table, array $columns, array $where = [])
    {
        try {
            self::getConn(); // Usar esse método estático para que a conexão seja feita, já que o método é estático (não depende de instância da classe)

            $query = "SELECT " . implode(", ", $columns) . " FROM $table";
            if ($where) {
                // Usar bindValue no where
                $query .= " WHERE ";
                foreach ($where as $key => $value) {
                    $query .= "$key = :$key";
                    if (next($where)) {
                        $query .= " AND ";
                    }
                }
    
                $stmt = self::$conn->prepare($query);
                foreach ($where as $key => $value) {
                    $stmt->bindValue(":$key", $value);
                }
            } else {
                $stmt = self::$conn->prepare($query);
            }
    
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_OBJ);
    
            return $result;
        } catch (\Throwable $th) {
            throw new MyAppException("Erro ao selecionar registro: " . $th->getMessage() . " | line: " . $th->getLine() . " | " . $th->getFile());
        }
    }

    public static function executeSelect(string $query, array $params = null) : array {
        try {
            self::getConn();
            $stmt = self::$conn->prepare($query);
            if($params){
                foreach ($params as $key => $value) {
                    $stmt->bindValue($key, $value);
                }
            }
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_OBJ);
            return $result;
        } catch (\Throwable $th) {
            throw new MyAppException("Erro ao executar seleção de dados", $th);
        }
    }

    public static function update(string $table, array $columns, array $values, string $where){
        // Example: update("users", ["name", "email"], ["Durov", "durov@telegram"], "id = 1");
        try {
            self::getConn(); // Usar esse método estático para que a conexão seja feita, já que o método é estático (não depende de instância da classe)

            $query = "UPDATE $table SET ";
            for ($i=0; $i < count($columns); $i++) { 
                $query .= "$columns[$i] = '$values[$i]' ";
                if($i < count($columns) - 1){
                    $query .= ", ";
                }
            }
            $query .= " WHERE $where";
            $stmt = self::$conn->prepare($query);
            return $stmt->execute();
        } catch (\Throwable $th) {
            //throw $th;
            return(["ok" => false, "message" => $th->getMessage(), "line" => $th->getLine()] );
        }
    }

    public static function update2(string $table, array $columns, array $values, array $where) {
        // Usar bind value para evitar SQL injection
        // Exemplo: update("users", ["name", "email"], ["Durov", "xxxxx@xxxxxxxx"], ["id" => 1]);
    }

    public static function delete(string $table, array $where = []) {
        // Example: delete("users", ["id" => 1]);
        try {
            self::getConn(); // Usar esse método estático para que a conexão seja feita, já que o método é estático (não depende de instância da classe)
            $query = "DELETE FROM $table";
            if($where){
                $query .= " WHERE ";
                foreach ($where as $key => $value) { // Usar bindValue no where
                    $query .= "$key = :$key";
                    if (next($where)) {
                        $query .= " AND ";
                    }
                }
                $stmt = self::$conn->prepare($query);
                foreach ($where as $key => $value) {
                    $stmt->bindValue(":$key", $value);
                }
            } else {
                // Se não houver where, não continuar, pois se não deleta tudo.
                return false;
            }
            
            $result = $stmt->execute();
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            // return (["ok" => false, "message" => $th->getMessage(), "line" => $th->getLine()]);
            throw new MyAppException("Erro ao deletar registro.", $th);
        }
    }

    public static function delete2(string $table, string $where){
        // Example: delete("users", "id = 1");
        try {
            self::getConn(); // Usar esse método estático para que a conexão seja feita, já que o método é estático (não depende de instância da classe)
            $query = "DELETE FROM $table WHERE $where";
            $stmt = self::$conn->prepare($query);
            $result = $stmt->execute();
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return (["ok" => false, "message" => $th->getMessage(), "line" => $th->getLine()]);
        }
    }


}

?>