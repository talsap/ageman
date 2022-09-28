<?php

namespace App\Apis\GoogleCalendar;
use Google;

class CalendarList{

    /**
     * MÉTODO RESPONSÁVEL POR VERIFICAR SE EXISTE UM CALENDÁRIO COM O TÍTULO ESPECÍFICADO
     * @param object $service [calendar]
     * @param string $summary
     * @return string
     */
    public static function VerifyExistCalendar($service, $summary){
        //RECEBE A LISTA COM OS CALENDÁRIOS DO USUÁRIO
        $list = $service->calendarList->listCalendarList()->getItems();
        
        //VERIFICA SE EXISTE O CALENDÁRIO RETORNANDO TRUE
        foreach($list as $i => $CalendarListEntry){
            if($CalendarListEntry->getSummary() == $summary){
                return true;
            }
        } 

        //CASO CONTRÁRIO RETORNA FALSO
        return false;  
    }

    public static function getCalendarSummary($service, $summary){
        //RECEBE A LISTA COM OS CALENDÁRIOS DO USUÁRIO
        $list = $service->calendarList->listCalendarList()->getItems();

        //VERIFICA SE EXISTE O CALENDÁRIO RETORNANDO O ID
        foreach($list as $i => $CalendarListEntry){
            if($CalendarListEntry->getSummary() == $summary){
                return $CalendarListEntry->getId();
            }
        } 
        
        //CASO CONTRÁRIO RETORNA VAZIO
        return ''; 
    }









}