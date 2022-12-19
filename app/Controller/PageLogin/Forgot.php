<?php

namespace App\Controller\PageLogin;

use \App\Utils\View;
use \App\Model\Entity\User;

class Forgot extends Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO (VIEW) DA PAGINA DE FORGOT
     * @param Request $request
     * @return string 
    */
    public static function getForgot($request, $errorMessage = null){
        //STATUS
        $status = !is_null($errorMessage) ? Alert::getErro($errorMessage) : '';

        //CONTEÚDO DA PÁGINA DE LOGIN
        $content = View::render('PageLogin/forgot', [
            'title'  => 'MANUUFRB - Login', 
            'name'   => 'MANUUFRB',
            'status' => $status
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPage('MANUUFRB - Recuperar', $content);
    }

    /**
     * MÉTODO RESPONSAVEL POR CRIAR A REDEFINIÇÃO DE SENHA DO USUÁRIO
     * @param Request $request
     * @return string
     */
    public static function setForgot($request){
        //POST VARS
        $postVars = $request->getPostVars();
        
        $email = $postVars['email'] ?? '';

        //BUSCA O USUÁRIO PELO E-MAIL
        $obUser = User::getUserByEmail($email);
        if(!$obUser instanceof User){
            return self::getForgot($request, 'E-mail não encontrado!');
        }

        

    }

}

