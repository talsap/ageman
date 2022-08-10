<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Home extends Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO A (VIEW) DE HOME DO PAINEL
     * @param Request $request
     * @return string 
    */
    public static function getHome($request){
        //CONTEÚDO DA PÁGINA 
        $content = View::render('pages/home', []);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPage('MANUUFRB', $content);
    }
}