<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Ordem extends Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO A (VIEW) ORDENS E SERVICOS DO PAINEL
     * @param Request $request
     * @return string 
    */
    public static function getOrdem($request){
        //CONTEÚDO DA PÁGINA 
        $content = View::render('pages/ordem/ordem', []);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB', $content, 'Ordem');
    }
}