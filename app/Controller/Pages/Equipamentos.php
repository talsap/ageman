<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Equipamentos extends Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO (VIEW) DA PAGINA EQUIPAMENTOS
     * @param Request $request
     * @return string 
    */
    public static function getEquipamentos($request){
        //CONTEÚDO DA PÁGINA 
        $content = View::render('pages/equipamentos/equipamentos', []);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Equipamentos', $content, 'Equipamentos');
    }

}