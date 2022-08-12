<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Home extends Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO A (VIEW) DE HOME DO PAINEL
     * @param Request $request
     * @return string 
    */
    public static function getHome($request){
        //CONTEÚDO DA PÁGINA 
        $content = View::render('Admin/home/home', []);

        //RETORNA O CONTEÚDO DO PAINEL
        return parent::getPanel('MANUUFRB - Home', $content, 'home');
    }
}