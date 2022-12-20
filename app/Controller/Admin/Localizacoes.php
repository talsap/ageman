<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Locais as EntityLocal;
use \App\File\Uploud;

class Localizacoes extends Page{

    /**
     * MÉTODO RESPONSÁVEL PRO OBTER A RENDERIZAÇÃO DOS ITENS DOS EQUIPAMENTOS PARA A PÁGINA
     * @param Request $request
     * @return string
     */
    private static function getAreaItens($request){
        //ITENS
        $itens  = '';

        //PEGA O ID DO USUÁRIO PELA SESSÃO
        $id_user = $_SESSION['admin']['usuario']['id'];

        //RESULTADOS DA PÁGINA
        $results = EntityLocal::getLocais('id_user ='.$id_user.' and area <> ""', 'id DESC', NULL);

        //RENDERIZA CADA LOCALIZAÇÃO
        while($obLocal = $results->fetchObject(EntityLocal::class)){
            $itens .= View::render('Admin/localizacoes/itens', [
                'id'         => $obLocal->id,        
                'id_user'    => $obLocal->id_user, 
                'local'      => $obLocal->local, 
                'area'       => $obLocal->area, 
            ]);
        }

        //RETORNA OS EQUIPAMENTOS
        return $itens;
    }

    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO (VIEW) DA PAGINA LOCALIZACAO
     * @param Request $request
     * @return string 
    */
    public static function getLocalizacoes($request){
        //CONTEÚDO DA PÁGINA 
        $content = View::render('Admin/localizacoes/localizacoes', [
            'title'     => 'Localizações',
            'botao1'    => 'Cadastrar Local',
            'type_btn1' => 'btn btn-primary btn-icon-split',
            'botao2'    => 'Cadastrar Área',
            'type_btn2' => 'btn btn-primary btn-icon-split',
            'botao3'    => 'Editar Local',
            'type_btn3' => 'btn btn-info btn-icon-split',
            'botao4'    => 'Excluir Local',
            'type_btn4' => 'btn btn-danger btn-icon-split',
            'option'    => self::getLocalItens($request),
            'itens'     => self::getAreaItens($request),

        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('AGEMAN - Localizações', $content, 'Localizacoes', $request);
    }

    /**
     * MÉTODO RESPONSAVEL POR DIRECIONAR PARA A (VIEW) DA PAGINA DE EDIÇÃO DA LOCALIZACAO
     * @param Request $request
     * @return string 
    */
    public static function setLocalizacoes($request){
        //DADOS DO POST
        $postVars = $request->getPostVars();

        //PEGA O ID DO LOCAL SELECIONADO
        $idLocal = $postVars['local'];
        
        //REDIRECIONA O USUÁRIO PARA A PAGINA DE EDICÃO DE UM LOCAL
        $request->getRouter()->redirect('/edit-local='.$idLocal);
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR O FORMULÁRIO DE EDIÇÂO DE UM LOCAL
     * @param Request $request
     * @return string
     */
    public static function getEditLocal($request, $id){
        //OBTÉM A INSTÂNCIA DO LOCAL NO BANCO DE DADOS
        $obLocal = EntityLocal::getLocal($id);

        //CAPTURA APENAS O LOCAL
        $local = $obLocal->local;

        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('Admin/localizacoes/local', [
            'title'     => 'Editar Local',
            'local'     => $local,
            'botao1'    => 'Voltar', 
            'type_btn1' => 'btn btn-secondary btn-icon-split',
            'botao2'    => 'Alterar', 
            'type_btn2' => 'btn btn-success btn-icon-split' ,
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('AGEMAN - Editar Local', $content, 'Localizacoes', $request);
    }

    /**
     * MÉTODO RESPONSÁVEL POR GRAVAR A ATUALIZAÇÃO DE UM LOCAL
     * @param Request $request
     * @return string
     */
    public static function setEditLocal($request, $id){
        //OBTÉM A INSTÂNCIA DO LOCAL NO BANCO DE DADOS
        $obLocal = EntityLocal::getLocal($id);
        
        //VALIDA A INSTANCIA CASO CONTRÁRIO RETORNA A PAGE LOCALIZACOES
        if(!$obLocal instanceof EntityLocal){
            $request->getRouter()->redirect('/localizacoes');
        }

        //POST VARS
        $postVars = $request->getPostVars();

        //ATUALIZA A INSTÂNCIA DO LOCAL
        $obLocal->id         = $id;
        $obLocal->id_user    = strval($_SESSION['admin']['usuario']['id']);
        $obLocal->localAnt   = $obLocal->local ?? '';
        $obLocal->local      = $postVars['local'] ?? $obLocal->local;
        
        //ATUALIZA O LOCAL BANCO DE DADOS
        $obLocal->atualizarLocal();

        //REDIRECIONA O USUÁRIO PARA A PAGINA DE LOCALIZACOES
        $request->getRouter()->redirect('/localizacoes?status=edited');
    }

    /**
     * MÉTODO RESPONSÁVEL PRO OBTER A RENDERIZAÇÃO DO SELECT DOS LOCAIS
     * @param Request $request
     * @return string
     */
    private static function getLocalItens($request){
        //ITENS
        $itens  = '';

        //PEGA O ID DO USUÁRIO PELA SESSÃO
        $id_user = $_SESSION['admin']['usuario']['id'];

        //RESULTADOS DA PÁGINA
        $results = EntityLocal::getLocais('id_user = '.$id_user.' '.'AND area = ""', 'id DESC', NULL);

        //RENDERIZA CADA EQUIPAMENTO
        while($obLocal = $results->fetchObject(EntityLocal::class)){
            $itens .= View::render('Admin/localizacoes/option', [
                'id'         => $obLocal->id,        
                'local'      => $obLocal->local,
                'selected'   => '',
            ]);
        }

        //RETORNA OS EQUIPAMENTOS
        return $itens;
    }

    /**
     * MÉTODO RESPONSÁVEL PRO OBTER A RENDERIZAÇÃO DO SELECT DOS LOCAIS COM O SELECTOR
     * @param Request $request
     * @return string
     */
    private static function getLocalItensSelector($request, $id){
        //ITENS
        $itens  = '';

        //OBTÉM A INSTÂNCIA DO LOCAL NO BANCO DE DADOS
        $ob = EntityLocal::getLocal($id);

        //CRIA A VARIÁVEL LOCAL PARA COMPARAÇÃO DO SELECTOR
        $lc = $ob->local;

        //PEGA O ID DO USUÁRIO PELA SESSÃO
        $id_user = $_SESSION['admin']['usuario']['id'];

        //RESULTADOS DA PÁGINA
        $results = EntityLocal::getLocais('id_user = '.$id_user.' '.'AND area = ""', 'id DESC', NULL);

        //RENDERIZA CADA EQUIPAMENTO
        while($obLocal = $results->fetchObject(EntityLocal::class)){
            if($lc == $obLocal->local) {
                $select = 'selected';
            }else{
                $select = '';
            }
            $itens .= View::render('Admin/localizacoes/option', [
                'id'         => $obLocal->id,        
                'local'      => $obLocal->local,
                'selected'   => $select,
            ]);
        }

        //RETORNA OS EQUIPAMENTOS
        return $itens;
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR O FORMULÁRIO DE CADASTRO DE UM NOVO LOCAL
     * @param Request $request
     * @return string
     */
    public static function getNewLocal($request){
        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('Admin/localizacoes/local', [
            'title'     => 'Cadastrar um Novo Local',
            'local'     => '',
            'botao1'    => 'Voltar', 
            'type_btn1' => 'btn btn-secondary btn-icon-split',
            'botao2'    => 'Cadastrar', 
            'type_btn2' => 'btn btn-success btn-icon-split' ,
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('AGEMAN - Cadastrar Local', $content, 'Localizacoes', $request);
    }

    /**
     * MÉTODO RESPONSÁVEL POR CADASTRAR UM LOCAL
     * @param Request $request
     * @return string
     */
    public static function setNewLocal($request){
        //DADOS DO POST
        $postVars = $request->getPostVars();

        //INSTÂNCIA DO LOCAL
        $obLocal = new EntityLocal();
        $obLocal->id_user           = strval($_SESSION['admin']['usuario']['id']);
        $obLocal->local             = $postVars['local'];
        
        //CADASTRA O LOCAL NO BANCO DE DADOS
        $obLocal->cadastrarLocal();
        
        //REDIRECIONA O USUÁRIO PARA A PAGE DE LOCALIZACOES
        $request->getRouter()->redirect('/localizacoes?status=created');
    }

    /**
     * MÉTODO RESPONSÁVEL POR EXCLUIR UM LOCAL
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setDeleteLocal($request, $id){
        //OBTÉM A INSTÂNCIA DO LOCAL NO BANCO DE DADOS
        $obLocal = EntityLocal::getLocal($id);

        //VALIDA A INSTANCIA
        if(!$obLocal instanceof EntityLocal){
            $request->getRouter()->redirect('/localizacoes');
        }

        //EXCLUI O LOCAL
        $obLocal->excluirLocal();
        
        //REDIRECIONA O USUÁRIO PARA A PAGINA LOCALIZAÇÕES
        $request->getRouter()->redirect('/localizacoes?status=deleted');
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR O FORMULÁRIO DE CADASTRO DE UMA NOVA ÁREA
     * @param Request $request
     * @return string
     */
    public static function getNewArea($request){
        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('Admin/localizacoes/area', [
            'title'     => 'Cadastrar Área',
            'option'     => self::getLocalItens($request),
            'area'      => '',
            'botao1'    => 'Voltar', 
            'type_btn1' => 'btn btn-secondary btn-icon-split',
            'botao2'    => 'Cadastrar', 
            'type_btn2' => 'btn btn-success btn-icon-split' ,
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('AGEMAN - Cadastrar Área', $content, 'Localizacoes', $request);
    }

    /**
     * MÉTODO RESPONSÁVEL POR CADASTRAR UMA ÁREA
     * @param Request $request
     * @return string
     */
    public static function setNewArea($request){
        //DADOS DO POST
        $postVars = $request->getPostVars();
        
        //PEGA O ID DO LOCAL
        $id = $postVars['cad-local'];

        //OBTÉM A INSTÂNCIA DO LOCAL NO BANCO DE DADOS
        $obLocal = EntityLocal::getLocal($id);

        //CRIA A VARIÁVEL LOCAL PARA A NOVA INSTÂNCIA
        $local = $obLocal->local;

        //CRIA UMA NOVA INSTÂNCIA DO LOCAL
        $obLocal = new EntityLocal();
        $obLocal->id_user       = strval($_SESSION['admin']['usuario']['id']);
        $obLocal->local         = $local;
        $obLocal->area          = $postVars['area'];
        
        //CADASTRA LOCAL E ÁREA NO BANCO DE DADOS
        $obLocal->cadastrarArea();
        
        //REDIRECIONA O USUÁRIO PARA A PAGE DE LOCALIZACOES
        $request->getRouter()->redirect('/new-area?status=created');
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR O FORMULÁRIO DE EDIÇÃO DE UMA ÁREA
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function getEditArea($request, $id){
        //OBTÉM O LOCAL
        $obLocal= EntityLocal::getLocal($id);

        //VALIDA A INSTANCIA
        if(!$obLocal instanceof EntityLocal){
            $request->getRouter()->redirect('/localizacoes');
        }

        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('Admin/localizacoes/area', [
            'title'     => 'Editar Área',
            'option'    => self::getLocalItensSelector($request, $id),
            'area'      => $obLocal->area,
            'botao1'    => 'Voltar', 
            'type_btn1' => 'btn btn-secondary btn-icon-split',
            'botao2'    => 'Alterar', 
            'type_btn2' => 'btn btn-success btn-icon-split',
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('AGEMAN - Editar Área', $content, 'Localizacoes', $request);
    }

    /**
     * MÉTODO RESPONSÁVEL POR ATUALIZAR UMA ÁREA
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setEditArea($request, $id){
        //DADOS DO POST
        $postVars = $request->getPostVars();
        
        //OBTÉM A INSTÂNCIA DO LOCAL NO BANCO DE DADOS DE ACORDO COM O ID
        $obLocal = EntityLocal::getLocal($id);

        //PEGA O ID DO LOCAL APENAS
        $idLocal = $postVars['cad-local'];

        //OBTÉM A INSTÂNCIA APENAS LOCAL NO BANCO DE DADOS
        $ob = EntityLocal::getLocal($idLocal);

        //CRIA A VARIÁVEL LOCAL PARA A NOVA INSTÂNCIA
        $local = $ob->local;

        $obLocal->id            = $obLocal->id ;
        $obLocal->id_user       = strval($_SESSION['admin']['usuario']['id']);
        $obLocal->localAnt      = $obLocal->local ?? '';
        $obLocal->local         = $local ?? $obLocal->local;
        $obLocal->areaAnt       = $obLocal->area ?? '';
        $obLocal->area          = $postVars['area'];
        
        //ATUALIZA LOCAL E ÁREA NO BANCO DE DADOS
        $obLocal->atualizarArea();
        
        //REDIRECIONA O USUÁRIO PARA A PAGE DE LOCALIZACOES
        $request->getRouter()->redirect('/localizacoes?status=edited');
    }

    /**
     * MÉTODO RESPONSÁVEL POR EXCLUIR UMA ÁREA
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setDeleteArea($request, $id){
        //OBTÉM A ÁREA DO BANCO DE DADOS
        $obLocal = EntityLocal::getLocal($id);

        //VALIDA A INSTANCIA        
        if(!$obLocal instanceof EntityLocal){
            $request->getRouter()->redirect('/localizacoes');
        }

        //EXCLUI A ÁREA
        $obLocal->excluirArea();
        
        //REDIRECIONA O USUÁRIO PARA A PAGE LOCALIZAÇÕES
        $request->getRouter()->redirect('/localizacoes?status=deleted');
    }
}
