<?php

namespace App\Controller\PageLogin;

use \App\Utils\View;
use \App\Model\Entity\User;
use \App\Session\Admin\Login as SessionAdminLogin;

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
        SessionAdminLogin::login($obUser);
        
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
        
        echo '<pre>';
        print_r($postVars);
        echo '</pre>'; exit;
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
        SessionAdminLogin::login($obUser);
        
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

