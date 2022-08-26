<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Locais as EntityLocal;
use \App\File\Uploud;

class Localizacoes extends Page{

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
            'option'    => self::getLocalItens($request)
            //'itens'     => self::getAreaItens($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Localizações', $content, 'Localizacoes', $request);
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
        return parent::getPanel('MANUUFRB - Editar Local', $content, 'Localizacoes', $request);
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
        $results = EntityLocal::getLocais('id_user ='.$id_user.' '.'AND area = ""', 'id DESC', NULL);

        //RENDERIZA CADA EQUIPAMENTO
        while($obLocal = $results->fetchObject(EntityLocal::class)){
            $itens .= View::render('Admin/localizacoes/option', [
                'id'         => $obLocal->id,        
                'local'      => $obLocal->local,
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
            'title'    => 'Cadastrar um Novo Local',
            'local'=> '',
            'botao1'    => 'Voltar', 
            'type_btn1' => 'btn btn-secondary btn-icon-split',
            'botao2'    => 'Cadastrar', 
            'type_btn2' => 'btn btn-success btn-icon-split' ,
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Cadastrar Local', $content, 'Localizacoes', $request);
    }

    /**
     * MÉTODO RESPONSÁVEL POR CADASTRAR UM EQUIPAMENTO
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
    public static function setDeleteLocal($request, $idLocal){
        //OBTÉM A INSTÂNCIA DO LOCAL NO BANCO DE DADOS
        $obLocal = EntityLocal::getLocal($idLocal);

        //VALIDA A INSTANCIA
        if(!$obLocal instanceof EntityLocal){
            $request->getRouter()->redirect('/localizacoes');
        }

        //EXCLUI O LOCAL
        $obLocal->excluirLocal();
        
        //REDIRECIONA O USUÁRIO PARA A PAGINA LOCALIZAÇÕES
        $request->getRouter()->redirect('/localizacoes?status=deleted');
    }
    
}
