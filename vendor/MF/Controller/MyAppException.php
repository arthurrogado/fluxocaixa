<?php

namespace MF\Controller;

// Classe de exceção personalizada para identificar facilmente suas exceções
class MyAppException extends \Exception {
    public function __construct($message, $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        echo json_encode(array('ok' => false, 'message' => $message));
        exit();
    }
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}

?>