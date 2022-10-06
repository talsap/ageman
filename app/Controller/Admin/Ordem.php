<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Utils\FullCalendar\FullCalendar as FC;
use \App\Model\Entity\Agendamentos as EntityAgendamentos;
use \App\Apis\GoogleCalendar as GC;
use Google;

class Ordem extends Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO A (VIEW) ORDENS E SERVICOS DO PAINEL
     * @param Request $request
     * @return string 
    */
    public static function getOrdem($request){
        //INICIA A VARIÁVEL
        $IDcalendar = '';
        
        //VERIFICA SE EXISTE O ID DO GOOGLE CALENDARIO NA SESSÃO DO USUÁRIO
        if($_SESSION['admin']['usuario']['idGoogleCalendar'] == ''){
            $IDcalendar = self::getIdGoogleCalendar();
            $_SESSION['admin']['usuario']['idGoogleCalendar'] = $IDcalendar;
        }else{
            $IDcalendar = $_SESSION['admin']['usuario']['idGoogleCalendar'];
        }

        $obj = str_replace('\n', 'n', self::getEventsManu());
        
        //CONTEÚDO DA PÁGINA 
        $content = View::render('Admin/ordem/ordem', [
            'googleCalendarId' => $IDcalendar,
            'eventsManu'       => $obj
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Ordens e Serviços', $content, 'Ordem', $request);
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR OS AGENDAMENTOS CRIADOS
     * @return Object
     */
    public static function getEventsManu(){
        //ITENS
        $itens  = [];

        //id DO USUÁRIO DA SESSÃO
        $id_user = $_SESSION['admin']['usuario']['id'];

        //RESULTADOS DO BANCO
        $results = EntityAgendamentos::getAgendamentos('id_user = "'.$id_user.'" and status <> ""', 'id DESC', NULL);
                
        //RENDERIZA CADA AGENDAMENTO
        while($obAgendamento = $results->fetchObject(EntityAgendamentos::class)){
            $itens = array_merge($itens, FC::getEventFullCalendar($obAgendamento));
        }

        return json_encode($itens);
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR O ID DO CALENDÁRIO GOOGLE
     * @return string
     */
    public static function getIdGoogleCalendar(){
        //ATIVA O BUFFER INTERNO DE SAÍDA
        ob_start();

        //PEGA OS TOKEN NA SESSÃO
        $id_token = $_SESSION['admin']['usuario']['id_token'];
        $access_token = $_SESSION['admin']['usuario']['access_token'];
        $refresh_token = $_SESSION['admin']['usuario']['refresh_token'];

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

        return $IDcalendar;
    }
}