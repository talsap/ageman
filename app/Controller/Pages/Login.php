<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Login{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR O CONTEÚDO (VIEW) DA NOSSA PAGINA DE LOGIN
     * @return string 
    */
    public static function getLogin(){
        return View::render('pages/login', ['title' => 'MANUUFRB - Login', 'name' => 'MANUUFRB']);
    }

}

