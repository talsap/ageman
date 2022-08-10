<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Alert{

    /**
     * MÉTODO RESPONSÁVEL POR RETORNA UMA MENSSAGEM DE ERRO
     * @param string $message
     * @return string
     */
    public static function getErro($message){
        return View::render( 'admin/alert/status', [
            'tipo' => 'danger',
            'mensagem' => $message
        ]);
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR RETORNA UMA MENSSAGEM DE SUCESSO
     * @param string $message
     * @return string
     */
    public static function getSuccess($message){
        return View::render( 'admin/alert/status', [
            'tipo' => 'success',
            'mensagem' => $message
        ]);
    }



}