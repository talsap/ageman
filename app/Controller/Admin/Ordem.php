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
        //ATIVA O BUFFER INTERNO DE SAÍDA
        ob_start();

        //PEGA OS TOKEN NA SESSÃO
        if(isset($_SESSION)){
            $id_token = $_SESSION['admin']['usuario']['id_token'];
            $access_token = $_SESSION['admin']['usuario']['access_token'];
            $refresh_token = $_SESSION['admin']['usuario']['refresh_token'];
        }
        
        //INSTÂNCIA OAUTH2 PARA API GOOGLE CALENDAR
        $client = new Google\Client();
        $client->setAccessToken($access_token);

        //VERIFICA SE O TOKEN DE ACESSO EXPIROU
        if($client->isAccessTokenExpired()){
            //CRIA UMA NOVA INSTÂNCIA DE CLIENTE GOOGLE
            $client->setClientId(ID_OAUTH);
            $client->setClientSecret(CLIENT_SECRET);
            
            //BUSCA UM NOVO TOKEN DE ACESSO OAUTH
            $access_token = $client->fetchAccessTokenWithRefreshToken($refresh_token);

            //ALTERA O TOKEN DE ACESSO NA SESSÃO DO USUÁRIO
            $_SESSION['admin']['usuario']['access_token'] = $access_token['access_token'];

            //ATUALIZA O NOVO TOKEN DE ACESSO
            $client->setAccessToken($access_token['access_token']);
        }

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
            
            //VERIFICA SE O USUÁRIO É PÚBLICO CASO CONTRÁRIO TORNA PÚBLICO
            $rule = $service->acl->get($IDcalendar, 'default');
            if($rule->getScope()->getType() != 'default'){
                $rule->getScope()->setType('default');
            }
        }

        //FINALIZA O BUFFER INTERNO
        ob_end_flush();

        //CONTEÚDO DA PÁGINA 
        $content = View::render('Admin/ordem/ordem', [
            'googleCalendarId' => $IDcalendar
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Ordens e Serviços', $content, 'Ordem', $request);
    }
}