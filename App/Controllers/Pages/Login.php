<?php

namespace App\Controllers\Pages;
use MF\Controller\Action;

class Login extends Action {

    public function index() {
        $this->render("login");
    }

    public function login() {
        $this->render("login");
    }

}

?>