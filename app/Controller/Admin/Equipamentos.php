<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Equipamentos as EntityEquipamentos;

class Equipamentos extends Page{
    
    /**
     * MÉTODO RESPONSÁVEL PRO OBTER A RENDERIZAÇÃO DOS ITENS DOS EQUIPAMENTOS PARA A PÁGINA
     * @param Request $request
     * @return string
     */
    private static function getEquipamentosItens($request){
        //EQUIPAMENTOS
        $itens  = '';

        //RENDERIZA O ITEM
        while($obEquipamentos = $results->fetchObject(EntityEquipamentos::class)){
            $itens .= View::render('Admin/equipamentos/itens', [
                'id'         => $obEquipamentos->id,        
                'patrimonio' => $obEquipamentos->patrimonio,
                'nome'       => $obEquipamentos->nome,
                'descricao'  => $obEquipamentos->descricao,
                'local'      => $obEquipamentos->local
            ]);
        }

        //RETORNA OS EQUIPAMENTOS
        return $itens;
    }

    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO (VIEW) DA PAGINA EQUIPAMENTOS
     * @param Request $request
     * @return string 
    */
    public static function getEquipamentos($request){
        //CONTEÚDO DA PÁGINA 
        $content = View::render('Admin/equipamentos/equipamentos', [
            //'itens' => self::getEquipamentosItens($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Equipamentos', $content, 'Equipamentos');
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR O FORMULÁRIO DE CADASTRO DE UM NOVO EQUIPAMENTO
     * @param Request $request
     * @return string
     */
    public static function getNewEquipamento($request){
        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('Admin/equipamentos/form', [

        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Cadastrar Equipamento', $content, 'Equipamentos');

    }

    /**
     * MÉTODO RESPONSÁVEL POR CADASTRAR UM EQUIPAMENTO
     * @param Request $request
     * @return string
     */
    public static function insertEquipamento($request){
        //DADOS DO POST
        $postVars = $request->getPostVars();

        //PEGA O ID DO USUÁRIO PELA SESSÃO
        $id = $_SESSION['admin']['usuario']['id'];

        //NOVA INSTÂNCIA DE DEPOIMENTO
        $obEquipamentos = new EntityEquipamentos;
        $obEquipamentos->id_user    = $id;
        $obEquipamentos->patrimonio = $postVars['patrimonio'];
        $obEquipamentos->nome       = $postVars['nome'];
        $obEquipamentos->descricao  = $postVars['descricao'];
        $obEquipamentos->local      = $postVars['local'];
        $obEquipamentos->dirImage   = $postVars['dirImage'];
        $obEquipamentos->hrsUso     = $postVars['hrsUso'];
        $obEquipamentos->status     = $postVars['status'];
        $obEquipamentos->histManu   = $postVars['histManu'];

        return self::getEquipamentos();
    }
}