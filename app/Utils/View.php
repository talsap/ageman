<?php

namespace App\Utils;

class View{
    /**
     * VARIÁVEIS PADRÕES DA VIEW
     * @var array
     */
    private static $vars = [];

    /**
     * MÉTODO RESPONSÁVEL POR DEFINIR OS DADOS INICIAIS DA CLASS
     * @param array
     */
    public static function init($vars = []){
        self::$vars = $vars;
    }
    
    /**
     * MÉTODO RESPONSÁVEL EM RETORNAR O CONTEÚDO DE UMA VIEW
     * @param string $view
     * @return string
     */
    private static function getContentView($view){
        $file = __DIR__.'/../../resources/view/'.$view.'.html';
        return file_exists($file) ? file_get_contents($file) : '';
    }
    
    /**
     * MÉTODO RESPONSÁVEL EM RETORNAR O CONTEÚDO RENDERIZADO DE UMA VIEW
     * @param string $view
     * @param array  $vars (string/numeric)
     * @return string
     */
    public static function render($view, $vars = []){
        //CONTEÚDO DA VIEW
        $contentView = self::getContentView($view);

        //MERGE DE VARIÁVES DA VIEW
        $vars = array_merge(self::$vars,$vars);

        //CHAVES DO ARRAY DE VARIÁVEIS
        $keys = array_keys($vars);
        $keys = array_map(function($item){
            return '{{'.$item.'}}';
        }, $keys);

        //RETORNA O CONTEÚDO REDENRIZADO
        return str_replace($keys,array_values($vars), $contentView);
    }

}
