<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use Google;

class Ordem extends Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO A (VIEW) ORDENS E SERVICOS DO PAINEL
     * @param Request $request
     * @return string 
    */
    public static function getOrdem($request){
        $token  = $_SESSION['admin']['usuario']['token'];
        $usuario = $_SESSION['admin']['usuario']['email'];

        //INSTÂNCIA OAUTH2 PARA API GOOGLE CALENDAR
        $client = new Google\Client();
        $client->setClientId(ID_OAUTH);
        $client->setClientSecret(CLIENT_SECRET);
        $client->addScope(Google\Service\Calendar::CALENDAR);
        $client->setRedirectUri(URL.'/login-google');
        $client->setAccessType('offline');
        $client->setIncludeGrantedScopes(true);
        $client->setLoginHint($usuario);
        $auth_url = $client->createAuthUrl();
        //$client->authenticate($_GET['code']);
        //$client->setAccessToken($token);
        //$tokenn = $client->verifyIdToken($token);
        
        //REDIRECIONA O USUÁRIO PARA AUTORIZAÇÃO
        //header('location: '.filter_var($auth_url, FILTER_SANITIZE_URL));
        
        
        echo '<pre>';
        print_r($_SESSION);
        echo '</pre>'; exit;

        $service = new Google\Service\Calendar($client);
        $results = $service->calendarList->listCalendarList();

        echo '<pre>';
        print_r($_SESSION);
        echo '</pre>'; exit;

        /////////////////////////

        //CONTEÚDO DA PÁGINA 
        $content = View::render('Admin/ordem/ordem', []);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Ordens e Serviços', $content, 'Ordem', $request);
    }
}