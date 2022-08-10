<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Responsaveis extends Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO (VIEW) DA PAGINA RESPONSAVEIS
     * @param Request $request
     * @return string 
    */
    public static function getResponsaveis($request){
        //CONTEÚDO DA PÁGINA 
        $content = View::render('pages/responsaveis', ['title' => 'MANUUFRB', 'name' => 'MANUUFRB']);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPage('MANUUFRB', $content);
    }

}