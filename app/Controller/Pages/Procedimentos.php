<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Procedimentos extends Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO (VIEW) DA PAGINA PROCEDIMENTOS
     * @param Request $request
     * @return string 
    */
    public static function getProcedimentos($request){
        //CONTEÚDO DA PÁGINA 
        $content = View::render('pages/procedimentos/procedimentos', []);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB', $content, 'Procedimentos');
    }

}