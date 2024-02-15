<?php

namespace MF\Controller;

class Controller
{

    public static function getPost(string $key)
    {
        try{
            if(!isset($_POST[$key])) return null;
            return filter_input(INPUT_POST, $key) ? filter_input(INPUT_POST, $key) : $_POST[$key];
        } catch(\Exception $e) {
            return null;
        }
    }

}