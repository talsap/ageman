<?php

namespace App\Controller\Admin;

use \DateTime;
use \App\Utils\View;
use \App\Model\Entity\Agendamentos as EntityAgendamentos;
use \App\Model\Entity\Equipamentos as EntityEquipamentos;
use \App\Model\Entity\Responsaveis as EntityResponsaveis;

class Agendamentos extends Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO (VIEW) DA PAGINA AGENDAMENTOS
     * @param Request $request
     * @return string 
    */
    public static function getAgendamentos($request){
        //CONTEÚDO DA PÁGINA 
        $content = View::render('Admin/agendamentos/agendamentos', [
            'title'    => 'Agenda de manutenções',
            'botao'    => 'Agendar Manutenções',
            'type_btn' => 'btn btn-primary btn-icon-split',
            'itens'    => ''
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Agendamentos', $content, 'Agendamentos', $request);
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR O FORMULÁRIO DE CADASTRO DE UM NOVO AGENDAMENTO
     * @param Request $request
     * @return string
     */
    public static function getNewAgendamento($request){
        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('Admin/agendamentos/form', [
            'title'     => 'Novo Agendamento',
            'titulo'    => '',
            'equip'     => self::getEquipamentosItens($request),
            'respons'   => self::getResponsaveisItens($request),
            'area'      => '',
            'descricao' => '',
            'botao1'    => 'Voltar', 
            'type_btn1' => 'btn btn-secondary btn-icon-split',
            'botao2'    => 'Agendar', 
            'type_btn2' => 'btn btn-success btn-icon-split' ,
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Agendamento', $content, 'Agendamentos', $request);
    }

    /**
     * MÉTODO RESPONSÁVEL EM OBTER A RENDERIZAÇÃO DOS ITENS DOS RESPONSAVEIS PARA A PÁGINA
     * @param Request $request
     * @return string
     */
    private static function getResponsaveisItens($request){
        //ITENS
        $itens  = '';

        //PEGA O ID DO USUÁRIO PELA SESSÃO
        $id_user = $_SESSION['admin']['usuario']['id'];

        //RESULTADOS DA PÁGINA
        $results = EntityResponsaveis::getResponsaveis('id_user ='. $id_user, 'id DESC', NULL);

        //RENDERIZA CADA RESPONSÁVEL
        while($obResponsaveis = $results->fetchObject(EntityResponsaveis::class)){
            $itens .= View::render('Admin/agendamentos/option', [
                'id'         => $obResponsaveis->id,        
                'value'       => $obResponsaveis->nome,
                'selected'   => ''
            ]);
        }

        //RETORNA OS RESPONSÁVEIS
        return $itens;
    }

    /**
     * MÉTODO RESPONSÁVEL EM OBTER A RENDERIZAÇÃO DOS ITENS DOS EQUIPAMENTOS PARA A PÁGINA
     * @param Request $request
     * @return string
     */
    private static function getEquipamentosItens($request){
        //ITENS
        $itens  = '';

        //PEGA O ID DO USUÁRIO PELA SESSÃO
        $id_user = $_SESSION['admin']['usuario']['id'];

        //RESULTADOS DA PÁGINA
        $results = EntityEquipamentos::getEquipamentos('id_user ='. $id_user, 'id DESC', NULL);

        //RENDERIZA CADA EQUIPAMENTO
        while($obEquipamentos = $results->fetchObject(EntityEquipamentos::class)){
            $itens .= View::render('Admin/agendamentos/option', [
                'id'         => $obEquipamentos->id,        
                'value'       => $obEquipamentos->patrimonio.' - '.$obEquipamentos->nome,
                'selected'   => ''
            ]);
        }

        //RETORNA OS EQUIPAMENTOS
        return $itens;
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR CRIAR UM NOVO AGENDAMENTO
     * @param Request $request
     * @return string
     */
    public static function setNewAgendamento($request){
        //DADOS DO POST
        $postVars = $request->getPostVars();

        //TIPO DE MANUTENÇÃO
        $tipos = self::getTipoManutencao($postVars['tipo'], $postVars['outro']);

        //CRIA AS VARIAVEIS DE SEMANA
        $dom = $postVars['dom'] ?? '';
        $seg = $postVars['seg'] ?? '';
        $ter = $postVars['ter'] ?? '';
        $qua = $postVars['qua'] ?? '';
        $qui = $postVars['qui'] ?? '';
        $sex = $postVars['sex'] ?? '';
        $sab = $postVars['sab'] ?? '';
        $dias_semana = Array($dom, $seg, $ter, $qua, $qui, $sex, $sab);

        //REPETIÇÕES
        $frequecia = self::getFrequeciaRepeticoes($postVars['replace'], $postVars['dia'], $postVars['semana'], $dias_semana, $postVars['mes'], $postVars['ano'], $postVars['duracao'], $postVars['num'], $postVars['data-ate-fs']);

        echo '<pre>';
        print_r($frequecia);
        echo '</pre>';

        echo '<pre>';
        print_r($postVars);
        echo '</pre>'; exit;

        //CRIA NOVA INSTÂNCIA DE AGENDAMENTO
        $obAgendamento = new EntityAgendamentos();
        $obAgendamento->id_user         = strval($_SESSION['admin']['usuario']['id']);
        $obAgendamento->id_equipamento  = strval($postVars['equipamento']) ?? NULL;
        //$obAgendamento->id_responsaveis = $postVars['resp'] ?? '';
        //$obAgendamento->title           = $postVars['title'] ?? '';
        $obAgendamento->dt_st           = 'VALUE=DATE:'.self::getDateFormatCalendar($postVars['data-st']) ?? '';
        $obAgendamento->dt_fs           = 'VALUE=DATE:'.self::getDateFormatCalendar($postVars['data-fs']) ?? '';
        //$obAgendamento->freq            =
        //$obAgendamento->alert           =
        //$obAgendamento->tipo            = $tipos ?? '';
        //$obAgendamento->inspecao        = strval($postVars['inspecao']);
        //$obAgendamento->descricao       = $postVars['descricao'] ?? '';
        //$obAgendamento->status          =

    }
    
    /**
     * TRANSFORMA O FORMATO DA DATA dd/mm/yyyy EM yyyymmdd
     * @param string $date
     * @return string
     */
    public static function getDateFormatCalendar($date){
        return DateTime::createFromFormat('d/m/Y', $date)->format('Ymd');
    }

    /**
     * MÉTODO RESPONSÁVEL EM OBTER A FREQUÊNCIA DE REPETIÇÕES
     * @param string $idt
     * @param string $dia
     * @param string $sem
     * @param Array  $d_sem
     * @param string $mes
     * @param string $ano
     * @param string $dur
     * @param string $num
     * @param string $dtfs
     * @return frequencia
     */
    public static function getFrequeciaRepeticoes($idt, $dia, $sem, $d_sem, $mes, $ano, $dur, $num, $dtfs){
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

        //VERIFICA SE O IDT EXISTE E PROSSEGUE PARA AS CONDIÇÕES
        if(isset($idt)){
            switch ($idt) {
                case '0':
                    return '';
                    break;
                case '1':
                    switch ($dur) {
                        case '0':
                            return 'RRULE:FREQ=DAILY;WKST=SU;INTERVAL='.$dia;
                            break;
                        case '1':
                            return 'RRULE:FREQ=DAILY;WKST=SU;COUNT='.$num.';INTERVAL='.$dia;
                            break;
                        case '2':
                            $dt = self::getDateFormatCalendar($dtfs);
                            return 'RRULE:FREQ=DAILY;WKST=SU;UNTIL='.$dt.'T235959Z;INTERVAL='.$dia;
                            break;
                    }                        
                    break;
                case '2':
                    switch ($dur) {
                        case '0':
                            return 'RRULE:FREQ=WEEKLY;WKST=SU;INTERVAL='.$sem.';BDAY='.$string_day;
                            break;
                        case '1':
                            return 'RRULE:FREQ=WEEKLY;WKST=SU;COUNT='.$num.';INTERVAL='.$sem.';BDAY='.$string_day;
                            break;
                        case '2':
                            $dt = self::getDateFormatCalendar($dtfs);
                            return 'RRULE:FREQ=WEEKLY;WKST=SU;UNTIL='.$dt.'T235959Z;INTERVAL='.$sem.';BDAY='.$string_day;
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
    
    /**
     * MÉTODO RESPONSÁVEL E DEFINIR O TIPO DE MANUTENÇÃO
     * @param string $idt
     * @param string $outro
     * @return tipodemanutencao
     */
    public static function getTipoManutencao($idt, $outro){
        if(isset($idt)){
            switch ($idt) {
                case '0':
                    return 'preventiva';
                    break;
                case '1':
                    return 'periodica';
                    break;
                case '2':
                    return 'limpeza';
                    break;
                case '3':
                    return 'troca de oleo';
                    break;
                case '4':
                    return 'troca de peca';
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
}