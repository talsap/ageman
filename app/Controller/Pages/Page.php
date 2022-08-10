<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR O CONTEÚDO (VIEW) DA ESTRUTURA DE PÁGINA GENÉRICA DO ADMIN
     * @param string $title
     * @param string $content
     * @return string 
    */
    public static function getPage($title, $content){
        return View::render('pages/page', [
            'title'   => $title,
            'content' => $content
        ]);
    }

    /**
     * MÉTODO RESPONSAVEL POR REDENRIZAR A (VIEW) DO PAINEL COM OS CONTEÚDOS DINÂMICOS
     * @param string $title
     * @param string $content
     * @return string 
    */
    public static function getPanel($title, $content, $current){
        return View::render('pages/page', [
            'title'   => $title,
            'content' => $content
        ]);
    }
}

