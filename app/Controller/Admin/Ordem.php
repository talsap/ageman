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
        $cookie = $_SESSION['admin']['usuario']['cookie'];

        //INSTÂNCIA DO CLIENTE GOOGLE
        $client = new Google\Client();
        $client->setClientId(ID_OAUTH);
        $client->setClientSecret(CLIENT_SECRET);
        $client->addScope('https://www.googleapis.com/auth/calendar');
        $client->setScopes(Google\Service\Calendar::CALENDAR_READONLY);
        $client->setIncludeGrantedScopes(true);
        $client->verifyIdToken($token);

        $service = new Google\Service\Calendar($client);
        //$results = $service->calendarList->listCalendarList();

        echo '<pre>';
        print_r($client);
        echo '</pre>'; exit;

        /////////////////////////

        //CONTEÚDO DA PÁGINA 
        $content = View::render('Admin/ordem/ordem', []);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Ordens e Serviços', $content, 'Ordem', $request);
    }
}