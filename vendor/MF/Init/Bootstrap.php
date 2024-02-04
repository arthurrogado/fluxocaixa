<?php

namespace MF\Init;
use App\Models\Usuario;

abstract class Bootstrap {

    private $routes;

    abstract protected function initRoutes();

    public function __construct() {
        $this->initRoutes();
        $this->run($this->getUrl());
    }

    public function getRoutes() {
        return $this->routes;
    }

    public function setRoutes(array $routes) {
        $this->routes = $routes;
    }

    protected function run($url) {
        foreach($this->getRoutes() as $key => $route) {

            // define a default route if "route->redirect" exists
            if(isset($route['redirect']) && $url == $route['route']) {
                $url = $route['redirect'];
            }

            if(isset($route['route']) && $url == $route['route']) {

                // Verify if the route is public, if not, verify if the user is logged in
                if(!isset($route['public']) || (isset($route['public']) && $route['public'] == false)) {
                    // session_start();
                    $usuario = Usuario::checkLogin();
                    // if(!isset($_SESSION['usuario']) || $_SESSION['usuario'] == '') {
                    if(!$usuario) {
                        echo json_encode(["message" => "Você não está logado!", "ok" => false, 'redirect' => "/login"]);
                        exit;
                    }
                }

                // rename, for example, controller "Pages/Pessoas" to "Pages\Pessoas"
                $route['controller'] = str_replace("/", "\\", $route['controller']);

                $class = "App\\Controllers\\".ucfirst($route['controller']);
                $controller = new $class;
                $action = $route['action'];
                $controller->$action();
                exit;
            } 
            
        }
        
        // echo "404";
        $class = "App\\Controllers\\Pages\\Index";
        $controller = new $class;
        $action = "_404";
        $controller->$action();

    }

    protected function getUrl() {
        // return parse_url($_SERVER['PATH_INFO'], PHP_URL_PATH);
        $request_url = $_SERVER['REQUEST_URI'];
        // Remove the first "/api" from url, used just for reference
        $request_url = substr($request_url, 4);
        return parse_url( $request_url , PHP_URL_PATH);
    }

}

?>