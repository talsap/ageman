<?php

namespace App\Controller\PageLogin;

use \App\Utils\View;
use \App\Model\Entity\User;
use \App\Session\Admin\Login as SessionAdminLogin;
use Google;

class Login extends Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO (VIEW) DA NOSSA PAGINA DE LOGIN
     * @param Request $request
     * @param string  $erroMessage
     * @return string 
    */
    public static function getLogin($request, $errorMessage = null){
        //STATUS
        $status = !is_null($errorMessage) ? Alert::getErro($errorMessage) : '';
        
        //CONTEÚDO DA PÁGINA DE LOGIN
        $content = View::render('PageLogin/login', [
            'title' => 'MANUUFRB - Login',
             'name' => 'MANUUFRB',
            'status' => $status
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPage('MANUUFRB - Login', $content);
    }

    /**
     * MÉTODO RESPONSÁVEL POR DEFINIR O LOGIN DO USUÁRIO
     * @param Request $request
     */
    public static function setLogin($request){
        //POST VARS
        $postVars = $request->getPostVars();
        
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? ''; 

        //BUSCA O USUÁRIO PELO E-MAIL
        $obUser = User::getUserByEmail($email);
        if(!$obUser instanceof User){
            return self::getLogin($request, 'E-mail ou senha inválidos');
        }

        //VERIFICA A SENHA DO USUÁRIO
        if(!password_verify($senha, $obUser->senha)){
            return self::getLogin($request, 'E-mail ou senha inválidos');
        }

        //CRIA A SESSÃO DE LOGIN
        SessionAdminLogin::login($obUser, '', '');
        
        //REDIRECIONA O USUÁRIO PARA O /ADMIN
        $request->getRouter()->redirect('/admin');
    }

    /**
     * MÉTODO RESPONSÁVEL POR DEFINIR O LOGIN DO USUÁRIO PELO GOOGLE
     * @param Request $request
     */
    public static function setLoginGoogle($request){
        //POST VARS
        $postVars = $request->getPostVars();

        //VERIFICA OS CAMPOS OBRIGATÓRIOS
        if(!isset($postVars['credential']) || !isset($postVars['g_csrf_token'])){
            //REDIRECIONA O USUÁRIO PARA O /
            $request->getRouter()->redirect('/');
            exit;
        }
        //COOKIE CSRF
        $cookie = $_COOKIE['g_csrf_token'] ?? '';

        //VERIFICA O VALOR DO COOKIE E O DO POST PARA O CSRF
        if($postVars['g_csrf_token'] != $cookie){
            //REDIRECIONA O USUÁRIO PARA O /
            $request->getRouter()->redirect('/');
            exit;
        }

        //INSTÂNCIA DO CLIENTE GOOGLE
        $client = new Google\Client();
        
        //OBTÉM OS DADOS DO USUÁRIO COM BASE NO JWT
        $payload = $client->verifyIdToken($postVars['credential']); //CREDENTIAL=JWT

        //VERIFICA OS DADOS DO PAYLOAD
        if(isset($payload['email'])){
            $email = $payload['email'] ?? '';
            //$idGoogle = $payload['sub'] ?? '';
        }else{
            //REDIRECIONA O USUÁRIO PARA O /
            $request->getRouter()->redirect('/');
            exit;
        }

        //BUSCA O USUÁRIO PELO E-MAIL
        $obUser = User::getUserByEmail($email);

        if(!$obUser instanceof User){
            return self::getLogin($request, 'Usuário inválido!');
        }

        //CRIA A SESSÃO DE LOGIN
        SessionAdminLogin::login($obUser, $postVars['credential'], $cookie);
        
        //REDIRECIONA O USUÁRIO PARA O /ADMIN
        $request->getRouter()->redirect('/admin');
    }

    /**
     * MÉTODO RESPONSÁVEL POR DESLOGAR O USUÁRIO
     * @param Request $request
     */
    public static function setLogout($request){
        //DESTROI A SESSÃO DE LOGIN
        SessionAdminLogin::logout();

        //REDIRECIONA O USUÁRIO PARA A TELA DE LOGIN
        $request->getRouter()->redirect('/');
    }
}

