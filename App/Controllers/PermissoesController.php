<?php

namespace App\Controllers;
use App\Middlewares\PermissionMiddleware;
use App\Models\Permissao;
use MF\Controller\MyAppException;
use MF\Controller\Controller;
use Throwable;

class PermissoesController extends Controller{

    public static function getAcoesPorControlador() 
    {
        // Apenas admin ou escritório pode acessar as permissões
        PermissionMiddleware::checkIsAdminOrEscritorio();

        $acoes = Permissao::getAcoesPorControlador();
        if(!$acoes) throw new MyAppException("Não achei as ações do sistema! Contate o administrador desse sistema!");

        echo json_encode(array('ok' => true, 'acoes' => $acoes));
    }
    
    public static function getAcoesPorControladorDeUsuario()
    {
        // Pegar todas as ações e marcar como true as que o usuário tem permissão no campo temPermissao

        // Apenas admin ou escritório pode acessar as permissões
        PermissionMiddleware::checkIsAdminOrEscritorio();

        $id_usuario = self::getPost('id_usuario');
        $acoes = Permissao::getAcoesPorControlador($id_usuario); // Todas as ações do sistema
        if(!$acoes) throw new MyAppException("Não achei as ações do sistema! Contate o administrador desse sistema!");

        // Pegar as permissões e marcar as ações que o usuário tem com temPermissao = true
        foreach($acoes as $acao) {
            $acao->temPermissao = Permissao::usuarioTemPermissao($id_usuario, $acao->id);
        }

        echo json_encode(array('ok' => true, 'acoes' => $acoes));
    }

    public function atualizarPermissoesUsuario()
    {
        try{
            // Apenas admin ou escritório pode atualizar as permissões
            PermissionMiddleware::checkIsAdminOrEscritorio();

            // Receber o id do usuário e as permissões
            $id_usuario = $this->getPost('id_usuario');
            $ids_acoes = $this->getPost('ids_acoes'); // array de ids de ações

            if(!$ids_acoes) $ids_acoes = []; // Se não tiver nenhuma permissão, enviar um array vazio

            // Deletar todas as permissões do usuário
            $status = Permissao::deletePermissoesUsuario($id_usuario);

            // Inserir as novas permissões
            foreach($ids_acoes as $id_acao) {
                $status = Permissao::createPermissao($id_usuario, $id_acao);
                if(!$status) throw new MyAppException("Erro ao criar permissão.");
            }

            echo json_encode(array('ok' => true, 'message' => 'Permissões atualizadas com sucesso!'));
        
        } catch(Throwable $th) {
            throw new MyAppException("Erro ao atualizar permissões: ", $th);
        }
    }

    public function usuarioTemPermissao()
    {
        try {
            // Apenas admin ou escritório pode verificar permissões
            PermissionMiddleware::checkIsAdminOrEscritorio();

            $id_usuario = $this->getPost('id_usuario');
            $id_metodo = $this->getPost('id_acao');

            $status = Permissao::usuarioTemPermissao($id_usuario, $id_metodo);

            echo json_encode(array('ok' => $status, 'temPermissao' => $status));
        } catch(Throwable $th) {
            throw new MyAppException("Erro ao verificar permissão: ", $th);
        }
    }

}