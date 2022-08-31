<?php

namespace App\Controller\Admin;

use \App\Utils\View;

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
            'title'    => 'Novo Agendamento',
            'patrimonio'=> '',
            'nome'      => '',
            'local'     => '',
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

}