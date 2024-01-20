<?php

namespace MF\Model;

use App\Connection;

class Container {

    public static function getModel($model) {
        // A ideia desse container é instanciar o modelo e já passar o objeto de conexão
        // mas isso não é necessário, pois o atributo $conn é estático e pode ser acessado
        // além que o construtor do MF\Model\Model pode fazer esse trabalho
        
        $class = "\\App\\Models\\".ucfirst($model);
        $conn = Connection::getDB();
        return new $class($conn);
    }

}

?>