<?php

namespace MF\Controller;

use Throwable;

// Classe de exceção personalizada para identificar facilmente suas exceções
class MyAppException extends \Exception {
    public function __construct($message, Throwable $th = null, $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $array = array(
            'ok' => false,
            'message' => $th == null ? 
                "Erro: " . $message : 
                "Erro: " . $message . " | " . $th->getMessage() . " | line: " . $th->getLine() . " | " . $th->getFile()
        );
        echo json_encode($array);
        exit();
    }
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}

?>