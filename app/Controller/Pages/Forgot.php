<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Forgot{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR O CONTEÚDO (VIEW) DA NOSSA PAGINA DE LOGIN
     * @return string 
    */
    public static function getForgot(){
        return View::render('pages/forgot', ['title' => 'MANUUFRB - Recuperar']);
    }

}

