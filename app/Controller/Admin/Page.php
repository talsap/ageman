<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Controller\PageLogin\Alert;
use \App\Session\Admin\Login as SessionAdminLogin;
use Google;

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
        'Agendamentos' => [
            'icon' => 'tasks',
            'label' => 'Agendamentos',
            'link' => URL.'/agendamentos'
        ],
        'Responsaveis' => [
            'icon' => 'users-cog',
            'label' => 'Responsáveis',
            'link' => URL.'/responsaveis'
        ],
        'Equipamentos' => [
            'icon' => 'cogs',
            'label' => 'Equipamentos',
            'link' => URL.'/equipamentos'
        ],
        'Localizacoes' => [
            'icon' => 'map-marker-alt',
            'label'=> 'Localizações',
            'link' => URL.'/localizacoes'
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
    private static function getPerfil($request){
        //PEGA OS DADOS DO USUÁRIO PELA SESSÃO
        $name       = $_SESSION['admin']['usuario']['nome'];
        $id_token   = $_SESSION['admin']['usuario']['id_token'] ?? '';
        
        //DEFINE UM CAMINHO PADRÃO PARA O FOTO DO PERFIL
        $foto = 'resources/img/uplouds/perfil/default-1.svg';

        //MODIFICA A IMAGEM DO PERFIL SE HOUVER LOGIN GOOGLE
        if($id_token != ''){
            //DEFINE UMA MARGEM DE MANOBRA PARA O JWT
            $jwt = new \Firebase\JWT\JWT;
            $jwt::$leeway = 5;

            //INSTÂNCIA DO GOOGLE CLIENT
            $client = new Google\Client();
            $user = $client->verifyIdToken($id_token);
            
            //VERIFICA SE EXISTE O CAMINHO DA IMAGEM
            if(isset($user['picture'])){
                $foto = $user['picture'];
            }else{
                //REDIRECIONA O USUÁRIO PARA A ROTA DE LOGOUT
                $request->getRouter()->redirect('/logout?status=session-expired');
            }
        }
        
        //RETORNA A RENDERIZAÇÃO DO PERFIL
        return View::render('Admin/perfil/perfil', [
            'name' => $name,
            'foto' => $foto
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
            'perfil'    => self::getPerfil($request),
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
                return Alert::getSuccess('Cadastrado com sucesso!');
                break;
            case 'agended':
                return Alert::getSuccess('Agendado com sucesso!');
                break;
            case 'edited':
                return Alert::getSuccess('Alterado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Excluído com sucesso!');
                break;
            case 'sent':
                return Alert::getSuccess('Enviado com sucesso!');
                break;
            case 'error':
                return Alert::getErro('Algum erro encontrado!');
                break;
        }

    }
}

