<?php

namespace App\Controller\PageLogin;

use \App\Utils\View;
use \App\Session\Admin\Login as SessionAdminLogin;

class Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR O CONTEÚDO (VIEW) DA ESTRUTURA DE PÁGINA GENÉRICA DO PAINEL 
     * @param string $title
     * @param string $content
     * @return string 
    */
    public static function getPage($title, $content){
        return View::render('PageLogin/page', [
            'title'   => $title,
            'content' => $content
        ]);
    }

}

