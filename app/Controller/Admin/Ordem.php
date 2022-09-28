<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Apis\GoogleCalendar as GC;
use Google;

class Ordem extends Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO A (VIEW) ORDENS E SERVICOS DO PAINEL
     * @param Request $request
     * @return string 
    */
    public static function getOrdem($request){
        //PEGA OS TOKEN NA SESSÃO
        $access_token  = $_SESSION['admin']['usuario']['access_token'];
        $refresh_token  = $_SESSION['admin']['usuario']['refresh_token'];

        //INSTÂNCIA OAUTH2 PARA API GOOGLE CALENDAR
        $client = new Google\Client();
        $client->setAccessToken($access_token);

        //INICIA A CLIENTE DE SERVICO
        $service = new Google\Service\Calendar($client);

        //VERIFICA SE O CALENDÁRIO ESPECIFICADO EXISTE PARA O CLIENTE DE SERVICO
        $lista = GC\CalendarList::VerifyExistCalendar($service, 'MANU');
        
        //CRIA O CALENDÁRIO SE NÃO EXISTIR CASO CONTRÁRIO PEGA O ID
        if($lista == false){
            //CRIA UM NOVO CALENDÁRIO
            $IDcalendar = GC\Calendar::CrateCalendarSummary($service, 'MANU');
        }else{
            //PEGA O ID DO CALENDÁRIO DE ACORDO COM O NOME
            $IDcalendar = GC\CalendarList::getCalendarSummary($service, 'MANU');
        }

        $events = $service->events->listEvents($IDcalendar);

        echo '<pre>';
        print_r($events);
        echo '</pre>'; exit;

        /////////////////////////

        //CONTEÚDO DA PÁGINA 
        $content = View::render('Admin/ordem/ordem', []);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Ordens e Serviços', $content, 'Ordem', $request);
    }
}