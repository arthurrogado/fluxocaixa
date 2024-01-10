<?php

namespace App;
use MF\Init\Bootstrap;

class Route extends Bootstrap {

    public function initRoutes() {

        session_start();

        $routes['404'] = array(
            'route' => '/404',
            'controller' => 'Pages/Index',
            'action' => '_404'
        );
        $routes['home'] = array(
            'route' => '/',
            'redirect' => '/home'
        );
        array_push($routes, [
            'route' => '/home',
            'controller' => 'Pages/Index',
            'action' => 'home'
        ]);


        // USUARIOS //
        $routes['usuarios'] = array(
            'route' => '/usuarios',
            'redirect' => '/usuarios/listar'
        );
            // pages
        array_push($routes, [
            'route' => '/usuarios/listar',
            'controller' => 'Pages/Usuarios',
            'action' => 'listar'
        ]);
        array_push($routes, [
            "route" => "/usuarios/criar",
            "controller" => "Pages/Usuarios",
            "action" => "criar"
        ]);
        array_push($routes, [
            "route" => "/usuarios/visualizar",
            "controller" => "Pages/Usuarios",
            "action" => "visualizar"
        ]);
            // api
        array_push($routes, [
            'route' => '/api/usuarios/criar',
            'controller' => 'UsuariosController',
            'action' => 'criarUsuario'
        ]);
        array_push($routes, [
            'route' => '/api/usuarios/listar',
            'controller' => 'UsuariosController',
            'action' => 'getUsuarios'
        ]);
        array_push($routes, [
            'route' => '/api/usuarios/visualizar',
            'controller' => 'UsuariosController',
            'action' => 'visualizarUsuario'
        ]);
        array_push($routes, [
            'route' => '/api/usuarios/editar',
            'controller' => 'UsuariosController',
            'action' => 'editarUsuario'
        ]);
        array_push($routes, [
            'route' => '/api/usuarios/mudar_senha',
            'controller' => 'UsuariosController',
            'action' => 'mudarSenhaUsuario'
        ]);
        array_push($routes, [
            'route' => '/api/usuarios/excluir',
            'controller' => 'UsuariosController',
            'action' => 'excluirUsuario'
        ]);


        // LOGIN

        $routes['tela_login'] = array(
            'route' => '/login',
            'controller' => 'Pages/Login',
            'action' => 'index',
            'public' => true
        );
        $routes['login'] = array(
            'route' => '/api/login',
            'controller' => 'AuthController',
            'action' => 'login',
            'public' => true
        );
        array_push($routes,[
            'route' => '/api/usuario/check_login',
            'controller' => 'AuthController',
            'action' => 'checkLogin',
            'public' => true
        ]);
        $routes['logout'] = array(
            'route' => '/logout',
            'controller' => 'AuthController',
            'action' => 'logout',
        );

        // ESCRITORIOS //
        array_push($routes, [
            'route' => '/escritorios',
            'redirect' => '/escritorios/listar'
        ]);
            // pages
        array_push($routes, [
            'route' => '/escritorios/listar',
            'controller' => 'Pages/Escritorios',
            'action' => 'listar'
        ]);
        array_push($routes,[
            'route' => '/escritorio/visualizar',
            'controller' => 'Pages/Escritorios',
            'action' => 'visualizar'
        ]);
            // api
        array_push($routes, [
            'route' => '/api/escritorios/listar',
            'controller' => 'EscritoriosController',
            'action' => 'getEscritorios'
        ]);
        array_push($routes, [
            'route' => '/api/escritorios/visualizar',
            'controller' => 'EscritoriosController',
            'action' => 'visualizarEscritorio'
        ]);
        array_push($routes, [
            'route' => '/api/escritorios/criar',
            'controller' => 'EscritoriosController',
            'action' => 'criarEscritorio'
        ]);
        array_push($routes, [
            'route' => '/api/escritorios/editar',
            'controller' => 'EscritoriosController',
            'action' => 'editarEscritorio'
        ]);
        array_push($routes, [
            'route' => '/api/escritorios/excluir',
            'controller' => 'EscritoriosController',
            'action' => 'excluirEscritorio'
        ]);

        // CAIXAS
            // pages
        array_push($routes, [
            'route' => '/caixas',
            'redirect' => '/caixas/listar'
        ]);
        array_push($routes,[
            'route' => '/caixas/listar',
            'controller' => 'Pages/CaixasPages',
            'action' => 'listar'
        ]);
            // api
        array_push($routes, [
            'route' => '/api/caixas/listar',
            'controller' => 'CaixasController',
            'action' => 'listarCaixas'
        ]);


        $this->setRoutes($routes);
    }



}

?>