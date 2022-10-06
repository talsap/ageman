<?php

namespace App\Utils\FullCalendar;

use \DateTime;

class FullCalendar{

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR AS LISTA DE EVENTOS PARA O FULLCALENDAR
     * @param Agendamento $obAgendamento
     * @return array
     */
    public static function getEventFullCalendar($obAgendamento){
        $title = $obAgendamento->title ?? '';
        $start = self::dataFullCalendar($obAgendamento->dt_st) ?? '';
        $end = self::dataFullCalendar($obAgendamento->dt_fs) ?? '';
        $dtst = $obAgendamento->dt_st ?? '';
        $dtfs = $obAgendamento->dt_fs ?? '';

        //RECRIA A STRING DE FREQUÊNCIA DE REPETIÇÕES
        $freq = explode(',', $obAgendamento->freq);
        $replace = $freq[0];
        $duracao = $freq[1];
        $count  = $freq[2];
        $until  = $freq[3];
        $dia    = $freq[4];
        $sem    = $freq[5];
        $mes    = $freq[6];
        $ano    = $freq[7];
        $dom    = $freq[8];
        $seg    = $freq[9];
        $ter    = $freq[10];
        $qua    = $freq[11];
        $qui    = $freq[12];
        $sex    = $freq[13];
        $sab    = $freq[14];
        $d_sem = Array($dom, $seg, $ter, $qua, $qui, $sex, $sab);

        $frequencia = self::getFrequeciaRepeticoes($replace, $dia, $sem, $d_sem, $mes, $ano, $duracao, $count, $dtst, $dtfs, '');

        if($replace != '0'){
            return array([
                'title' => $title,
                'start' => $start,
                //'rrule' => $frequencia
                ]
            );
        }else{
            return array([
                'title' => $title,
                'start' => $start,
                'end'   => $end,
                //'rrule' => $frequencia
                ]
            );
        }
    }

    /**
     * MÉTODO RESPONSÁVEL EM CONVERTER O FORMATO DA DATA d/m/Y PARA Y-m-d
     * @param string $data
     * @return string
     */
    public static function dataFullCalendar($data){
        return DateTime::createFromFormat('d/m/Y', $data)->format('Y-m-d');
    }

    /**
     * MÉTODO RESPONSÁVEL EM CONVERTER O FORMATO DA DATA d/m/Y PARA Ymd
     * @param string $date
     * @return string
     */
    public static function getDateFormatCalendar($date){
        return DateTime::createFromFormat('d/m/Y', $date)->format('Ymd');
    }

    /**
     * MÉTODO RESPONSÁVEL EM OBTER A FREQUÊNCIA RRULE DE REPETIÇÕES
     * @param string $idt
     * @param string $dia
     * @param string $sem
     * @param Array  $d_sem
     * @param string $mes
     * @param string $ano
     * @param string $dur
     * @param string $num
     * @param string $dtfs
     * @param string $tz
     * @return frequencia
     */
    public static function getFrequeciaRepeticoes($idt, $dia, $sem, $d_sem, $mes, $ano, $dur, $num, $dtst, $dtfs, $tz){
        //RECEBE 1 CASO SEJA NULO
        if($dia == ''){ $dia = '1';}
        if($sem == ''){ $sem = '1';}
        if($mes == ''){ $mes = '1';}
        if($ano == ''){ $ano = '1';}

        //VERIFICA E CONCATENA CADA DIA DA SEMANA COM UMA VÍRGULA
        $string_day = '';
        for($i = 0; $i < count($d_sem); ++$i){
            if($d_sem[$i] != ''){
                $string_day = $string_day.$d_sem[$i].',';
            }
        }
        $string_day = rtrim($string_day, ',');

        //DETERMINA OS DATE START E FINISH
        $dtst = self::getDateFormatCalendar($dtst);
        $dtfs = self::getDateFormatCalendar($dtfs);

        //PROSSEGUE PARA AS CONDIÇÕES
        switch ($idt) {
            case '0':
                return '';
                break;
            case '1':
                switch ($dur) {
                    case '0':
                        return 'DTSTART:'.$dtst.$tz.'\nRRULE:FREQ=DAILY;WKST=SU;INTERVAL='.$dia;
                        break;
                    case '1':
                        return 'DTSTART:'.$dtst.$tz.'\nRRULE:FREQ=DAILY;WKST=SU;COUNT='.$num.';INTERVAL='.$dia;
                        break;
                    case '2':
                        return 'DTSTART:'.$dtst.$tz.'\nRRULE:FREQ=DAILY;WKST=SU;UNTIL='.$dtfs.$tz.';INTERVAL='.$dia;
                        break;
                }                        
                break;
            case '2':
                switch ($dur) {
                    case '0':
                        return 'DTSTART:'.$dtst.$tz.'\nRRULE:FREQ=WEEKLY;WKST=SU;INTERVAL='.$sem.';BDAY='.$string_day;
                        break;
                    case '1':
                        return 'DTSTART:'.$dtst.$tz.'\nRRULE:FREQ=WEEKLY;WKST=SU;COUNT='.$num.';INTERVAL='.$sem.';BDAY='.$string_day;
                        break;
                    case '2':
                        return 'DTSTART:'.$dtst.$tz.'\nRRULE:FREQ=WEEKLY;WKST=SU;UNTIL='.$dtfs.$tz.';INTERVAL='.$sem.';BDAY='.$string_day;
                        break;
                }                        
                break;
            case '3':
                return '';
                break;
            case '4':
                return '';
                break;
            case '5':
                return $outro;
                break;
            default:
                return '';
                break;
        }
    }
}