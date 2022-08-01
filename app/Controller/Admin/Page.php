<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR O CONTEÚDO (VIEW) DA ESTRUTURA DE PÁGINA GENÉRICA DO PAINEL 
     * @param string $title
     * @param string $content
     * @return string 
    */
    public static function getPage($title, $content){
        return View::render('admin/page', [
            'title'   => $title,
            'content' => $content
        ]);
    }

}

