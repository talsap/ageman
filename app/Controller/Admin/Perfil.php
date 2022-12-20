<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Perfil extends Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO A (VIEW) PEFIL
     * @param Request $request
     * @return string 
    */
    public static function getPerfil($request){
        //CONTEÚDO DA PÁGINA 
        $content = View::render('Admin/perfil/page', []);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('AGEMAN - Perfil', $content, '', $request);
    }
}