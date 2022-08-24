<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Responsaveis as EntityResponsaveis;

class Responsaveis extends Page{
    
    /**
     * MÉTODO RESPONSÁVEL PRO OBTER A RENDERIZAÇÃO DOS ITENS PARA A PÁGINA RESPONSÁVEIS
     * @param Request $request
     * @return string
     */
    private static function getResponsaveisItens($request){
        //RESPONSAVEIS
        $itens  = '';

        //PEGA O ID DO USUÁRIO PELA SESSÃO
        $id_user = $_SESSION['admin']['usuario']['id'];

        //RESULTADOS DA PÁGINA
        $results = EntityResponsaveis::getResponsaveis('id_user ='. $id_user, 'id DESC', NULL);

        //RENDERIZA CADA RESPONSÁVEL
        while($obResponsaveis = $results->fetchObject(EntityResponsaveis::class)){
            $itens .= View::render('Admin/responsaveis/itens', [
                'id'         => $obResponsaveis->id,        
                'nome'       => $obResponsaveis->nome,
                'email'      => $obResponsaveis->email,
            ]);
        }

        //RETORNA OS RESPONSÁVEIS
        return $itens;
    }

    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO (VIEW) DA PAGINA RESPONSAVEIS
     * @param Request $request
     * @return string 
    */
    public static function getResponsaveis($request){
        //CONTEÚDO DA PÁGINA 
        $content = View::render('Admin/responsaveis/responsaveis', [
            'title'    => 'Responsáveis',
            'botao'    => 'Cadastrar Responsável',
            'type_btn' => 'btn btn-primary btn-icon-split',
            'itens'    => self::getResponsaveisItens($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Responsáveis', $content, 'Responsaveis', $request);
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR O FORMULÁRIO DE CADASTRO DE UM NOVO RESPONSÁVEL
     * @param Request $request
     * @return string
     */
    public static function getNewResponsible($request){
        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('Admin/responsaveis/form', [
            'title'    => 'Cadastrar um Novo Responsável',
            'nome'      => '',
            'email'     => '',
            'botao1'    => 'Voltar', 
            'type_btn1' => 'btn btn-secondary btn-icon-split',
            'botao2'    => 'Cadastrar', 
            'type_btn2' => 'btn btn-success btn-icon-split' ,
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Cadastrar Responsável', $content, 'Responsaveis', $request);
    }

    /**
     * MÉTODO RESPONSÁVEL POR CADASTRAR UM NOVO RESPONSÁVEL
     * @param Request $request
     * @return string
     */
    public static function setNewResponsible($request){
        //DADOS DO POST
        $postVars = $request->getPostVars();

        //INSTÂNCIA DO RESPONSAVEL
        $obResponsavel = new EntityResponsaveis();
        $obResponsavel->id_user    = strval($_SESSION['admin']['usuario']['id']);
        $obResponsavel->nome       = $postVars['nome'];
        $obResponsavel->email      = $postVars['email'];
        
        //CADASTRA O RESPONSÁVEL NO BANCO DE DADOS
        $obResponsavel->cadastrar();
        
        //REDIRECIONA O USUÁRIO PARA A PAGE RESPONSÁVEIS
        $request->getRouter()->redirect('/new-responsible?status=created');
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR O FORMULÁRIO DE EDIÇÃO DE UM RESPONSÁVEL
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function getEditResponsible($request, $id){
        //OBTÉM O RESPONSÁVEL DO BANCO DE DADOS
        $obResponsavel = EntityResponsaveis::getResp($id);

        //VALIDA A INSTANCIA
        if(!$obResponsavel instanceof EntityResponsaveis){
            $request->getRouter()->redirect('/responsaveis');
        }

        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('Admin/responsaveis/form', [
            'title'     => 'Editar Responsável',
            'nome'      => $obResponsavel->nome,
            'email'     => $obResponsavel->email,
            'botao1'    => 'Voltar', 
            'type_btn1' => 'btn btn-secondary btn-icon-split',
            'botao2'    => 'Alterar', 
            'type_btn2' => 'btn btn-success btn-icon-split',
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Editar Responsável', $content, 'Responsaveis', $request);
    }

    /**
     * MÉTODO RESPONSÁVEL GRAVAR A ATUALIZAÇAO DE EDIÇÃO DE UM RESPONSÁVEL
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setEditResponsible($request, $id){
        //OBTÉM O RESPONSÁVEL NO BANCO DE DADOS
        $obResponsavel = EntityResponsaveis::getResp($id);

        //VALIDA A INSTANCIA
        if(!$obResponsavel instanceof EntityResponsaveis){
            $request->getRouter()->redirect('/equipamentos');
        }

        //POST VARS
        $postVars = $request->getPostVars();

        //ATUALIZA A INSTÂNCIA DO RESPONSÁVEL
        $obResponsavel->id         = $id;
        $obResponsavel->id_user    = strval($_SESSION['admin']['usuario']['id']);
        $obResponsavel->nome       = $postVars['nome'] ?? $obResponsavel->nome;
        $obResponsavel->email      = $postVars['email'] ?? $obResponsavel->email;
        
        //ATUALIZA O RESPONSÁVEL NO BANCO DE DADOS
        $obResponsavel->atualizar();
        
        //REDIRECIONA O USUÁRIO PARA A PAGE RESPONSÁVEL
        $request->getRouter()->redirect('/responsaveis?status=edited');
    }

    /**
     * MÉTODO RESPONSÁVEL POR EXCLUIR UM RESPONSÁVEL
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setDeleteResponsible($request, $id){
        //OBTÉM O RESPONSÁVEL NO BANCO DE DADOS
        $obResponsavel = EntityResponsaveis::getResp($id);

        //VALIDA A INSTANCIA
        if(!$obResponsavel instanceof EntityResponsaveis){
            $request->getRouter()->redirect('/responsaveis');
        }

        //EXCLUI O RESPONSÁVEL
        $obResponsavel->excluir();
        
        //REDIRECIONA O USUÁRIO PARA A PAGE EQUIPAMENTOS
        $request->getRouter()->redirect('/responsaveis?status=deleted');
    }
}