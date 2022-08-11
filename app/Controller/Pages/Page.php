<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Page{

    /**
     * MÓDULOS DISPONÍVEIS NO MENU
     * @var array
     */
    private static $modules = [
        'Ordem' => [
            'icon' => 'table',
            'label' => 'Ordens e Serviços',
            'link' => URL.'/ordens-servicos'
        ],
        'Procedimentos' => [
            'icon' => 'clipboard-list',
            'label' => 'Procedimentos',
            'link' => URL.'/procedimentos'
        ],
        'Responsaveis' => [
            'icon' => 'user',
            'label' => 'Responsáveis',
            'link' => URL.'/responsaveis'
        ],
        'Equipamentos' => [
            'icon' => 'wrench',
            'label' => 'Equipamentos',
            'link' => URL.'/equipamentos'
        ]
    ];

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
     * MÉTODO RESPONSÁVEL POR RENDERIZAR A VIEW DO PERFIL DO PAINEL
     * @return string
     */
    private static function getPerfil(){
        //RETORNA A RENDERIZAÇÃO DO PERFIL
        return View::render('pages/perfil/perfil', []);

    }

    /**
     * MÉTODO RESPONSÁVEL POR RENDERIZAR A VIEW DO MENU DO PAINEL
     * @param string $currentModule
     * @return string
     */
    private static function getMenu($currentModule){
        //LINKS DO MENU
        $links = '';

        //ITERA OS MÓDULOS
        foreach(self::$modules as $hash=>$module){
            $links .= View::render('pages/menu/links',[
            'icon' => $module['icon'],
            'label' => $module['label'],
            'link' => $module['link'],
            'active' => $hash == $currentModule ? 'active' : ''
            ]);
        }
        //RETORNA A RENDERIZAÇÃO DO MENU
        return View::render('pages/menu/menu', [
            'links' => $links
        ]);
    }

    /**
     * MÉTODO RESPONSAVEL POR REDENRIZAR A (VIEW) DO PAINEL COM OS CONTEÚDOS DINÂMICOS
     * @param string $title
     * @param string $content
     * @return string 
    */
    public static function getPanel($title, $content, $currentModule){
        //RENDERIZA A VIEW DO PAINEL
        $contentPanel = View::render('pages/panel/panel', [
            'menu' => self::getMenu($currentModule),
            'perfil' => self::getPerfil(),
            'conteudo' => $content
        ]);

        //RETORNA A PAGINA RENDERIZADA
        return self::getPage($title, $contentPanel);
    }
}

