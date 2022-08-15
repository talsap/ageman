<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Controller\PageLogin\Alert;

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
            'icon' => 'cogs',
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
        return View::render('Admin/page', [
            'title'   => $title,
            'content' => $content
        ]);
    }

    /**
     * MÉTODO RESPONSÁVEL POR RENDERIZAR A VIEW DO PERFIL DO PAINEL
     * @return string
     */
    private static function getPerfil(){
        //PEGA O NOME DO USUÁRIO PELA SESSÃO
        $name = $_SESSION['admin']['usuario']['nome'];
        
        //RETORNA A RENDERIZAÇÃO DO PERFIL
        return View::render('Admin/perfil/perfil', [
            'name' => $name
        ]);
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
            $links .= View::render('Admin/menu/links',[
            'icon' => $module['icon'],
            'label' => $module['label'],
            'link' => $module['link'],
            'active' => $hash == $currentModule ? 'active' : ''
            ]);
        }
        //RETORNA A RENDERIZAÇÃO DO MENU
        return View::render('Admin/menu/menu', [
            'links' => $links
        ]);
    }

    /**
     * MÉTODO RESPONSAVEL POR REDENRIZAR A (VIEW) DO PAINEL COM OS CONTEÚDOS DINÂMICOS
     * @param string $title
     * @param string $content
     * @return string 
    */
    public static function getPanel($title, $content, $currentModule, $request){
        //RENDERIZA A VIEW DO PAINEL
        $contentPanel = View::render('Admin/panel/panel', [
            'menu'      => self::getMenu($currentModule),
            'perfil'    => self::getPerfil(),
            'conteudo'  => $content,
            'status'    => self::getStatus($request)
        ]);

        //RETORNA A PAGINA RENDERIZADA
        return self::getPage($title, $contentPanel);
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR A MSG DE STATUS
     * @param Request $request
     * @return string
     */
    private static function getStatus($request){
        //QUERY PARAMS
        $queryParams = $request->getQueryParams();
        
        //STATUS
        if(!isset($queryParams['status'])) return '';

        //MENSAGENS DE STATUS
        switch ($queryParams['status']){
            case 'created':
                return Alert::getSuccess('Criado com sucesso!');
                break;
            case 'edited':
                return Alert::getSuccess('Editado com sucesso!');
                break;
        }

    }
}

