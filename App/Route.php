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
        array_push($routes,[
            'route' => '/caixas/visualizar',
            'controller' => 'Pages/CaixasPages',
            'action' => 'visualizar'
        ]);
            // api
        array_push($routes, [
            'route' => '/api/caixas/listar',
            'controller' => 'CaixasController',
            'action' => 'listarCaixas'
        ]);
        array_push($routes, [
            'route' => '/api/caixas/criar',
            'controller' => 'CaixasController',
            'action' => 'abrirCaixa'
        ]);
        array_push($routes, [
            'route' => '/api/caixas/visualizar',
            'controller' => 'CaixasController',
            'action' => 'visualizarCaixa'
        ]);
        array_push($routes, [
            'route' => '/api/caixas/editar',
            'controller' => 'CaixasController',
            'action' => 'editarCaixa'
        ]);
        array_push($routes, [
            'route' => '/api/caixas/excluir',
            'controller' => 'CaixasController',
            'action' => 'excluirCaixa'
        ]);


        // OPERAÇÕES
            // pages
            // api
        array_push($routes, [
            'route' => '/api/operacoes/caixa',
            'controller' => 'OperacoesController',
            'action' => 'getOperacoesCaixa'
        ]);
        array_push($routes, [
            'route' => '/api/operacoes/criar',
            'controller' => 'OperacoesController',
            'action' => 'criarOperacao'
        ]);
        array_push($routes, [
            'route' => '/api/operacoes/excluir',
            'controller' => 'OperacoesController',
            'action' => 'excluirOperacao'
        ]);
        array_push($routes, [
            'route' => '/api/operacoes/editar',
            'controller' => 'OperacoesController',
            'action' => 'editarOperacao'
        ]);

        // CARTEIRAS
            // api
        array_push($routes, [
            'route' => '/api/carteiras/obter_de_escritorio',
            'controller' => 'CarteirasController',
            'action' => 'getCarteirasDeEscritorio'
        ]);
        array_push($routes, [
            'route' => '/api/carteiras/obter_de_caixa',
            'controller' => 'CarteirasController',
            'action' => 'getCarteirasDeCaixa'
        ]);

        // PERMISSÕES
            // api
        array_push($routes, [
            'route' => '/api/permissoes/get_acoes_por_controlador',
            'controller' => 'PermissoesController',
            'action' => 'getAcoesPorControlador'
        ]);
        array_push($routes, [
            'route' => '/api/permissoes/get_acoes_por_controlador_de_usuario',
            'controller' => 'PermissoesController',
            'action' => 'getAcoesPorControladorDeUsuario'
        ]);
        array_push($routes, [
            'route' => '/api/permissoes/atualizar',
            'controller' => 'PermissoesController',
            'action' => 'atualizarPermissoesUsuario'
        ]);
        array_push($routes, [
            'route' => '/api/permissoes/usuario_tem_permissao',
            'controller' => 'PermissoesController',
            'action' => 'usuarioTemPermissao'
        ]);

        $this->setRoutes($routes);
    }



}