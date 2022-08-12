<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Procedimentos extends Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO (VIEW) DA PAGINA PROCEDIMENTOS
     * @param Request $request
     * @return string 
    */
    public static function getProcedimentos($request){
        //CONTEÚDO DA PÁGINA 
        $content = View::render('Admin/procedimentos/procedimentos', []);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Procedimentos', $content, 'Procedimentos');
    }

}