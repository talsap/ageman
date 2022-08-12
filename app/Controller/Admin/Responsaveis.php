<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Responsaveis extends Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO (VIEW) DA PAGINA RESPONSAVEIS
     * @param Request $request
     * @return string 
    */
    public static function getResponsaveis($request){
        //CONTEÚDO DA PÁGINA 
        $content = View::render('Admin/responsaveis/responsaveis', []);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Responsáveis', $content, 'Responsaveis');
    }

}