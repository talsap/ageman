<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Forgot extends Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO (VIEW) DA NOSSA PAGINA DE FORGOT
     * @param Request $request
     * @return string 
    */
    public static function getForgot($request){
        //CONTEÚDO DA PÁGINA DE LOGIN
        $content = View::render('admin/forgot', ['title' => 'MANUUFRB - Login', 'name' => 'MANUUFRB']);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPage('MANUUFRB - recuperar', $content);
    }

}

