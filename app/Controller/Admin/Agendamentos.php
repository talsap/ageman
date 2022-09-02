<?php

namespace App\Controller\Admin;

use \App\Utils\View;
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
            'botao2'    => 'Cadastrar', 
            'type_btn2' => 'btn btn-success btn-icon-split' ,
            'link'      => '' //link = {{src="resources/img/uplouds/User1/equipamentos/01.png"}}
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
}