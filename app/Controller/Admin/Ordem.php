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
        $id_token      = $_SESSION['admin']['usuario']['id_token'];
        $access_token  = $_SESSION['admin']['usuario']['id_token'];
        $email         = $_SESSION['admin']['usuario']['email'];

        //INSTÂNCIA OAUTH2 PARA API GOOGLE CALENDAR
        $client = new Google\Client();
        $client->setClientId(ID_OAUTH);
        $client->setClientSecret(CLIENT_SECRET);
        $client->addScope(Google\Service\Calendar::CALENDAR);
        //$client->setRedirectUri(URL.'/login-google');
        $client->setAccessType('offline');
        $client->setIncludeGrantedScopes(true);
        //$client->setLoginHint($email);
        $client->setAccessToken($access_token);
        //$client->fetchAccessTokenWithAuthCode($access_token);
        $token = $client->verifyIdToken($id_token);
        //$client->authenticate($access_token);
        //$client->setDeveloperKey($access_token);
        
        //REDIRECIONA O USUÁRIO PARA AUTORIZAÇÃO
        //header('location: '.filter_var($auth_url, FILTER_SANITIZE_URL));

        echo '<pre>';
        print_r($client->isAccessTokenExpired());
        echo '</pre>';

        $service = new Google\Service\Calendar($client);
        $calendar = $service->calendars->get('primary');

        echo '<pre>';
        print_r($calendar->getSummary());
        echo '</pre>'; exit;

        /////////////////////////

        //CONTEÚDO DA PÁGINA 
        $content = View::render('Admin/ordem/ordem', []);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Ordens e Serviços', $content, 'Ordem', $request);
    }
}