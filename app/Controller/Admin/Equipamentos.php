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

        //PEGA O ID DO USUÁRIO PELA SESSÃO
        $id_user = $_SESSION['admin']['usuario']['id'];

        //RESULTADOS DA PÁGINA
        $results = EntityEquipamentos::getEquipamentos('id_user ='. $id_user, 'id DESC', NULL);

        //RENDERIZA CADA EQUIPAMENTO
        while($obEquipamentos = $results->fetchObject(EntityEquipamentos::class)){
            $itens .= View::render('Admin/equipamentos/itens', [
                'id'         => $obEquipamentos->id,        
                'patrimonio' => $obEquipamentos->patrimonio,
                'nome'       => $obEquipamentos->nome,
                'descricao'  => $obEquipamentos->descricao,
                'local'      => $obEquipamentos->local,
                'imagem'     => $obEquipamentos->imagem,
                'horas'      => $obEquipamentos->horas,
                'status'     => $obEquipamentos->status,
                'hist_manu'  => $obEquipamentos->hist_manu,
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
            'title'    => 'Equipamentos',
            'botao'    => 'Cadastrar Equipamento',
            'type_btn' => 'btn btn-primary btn-icon-split',
            'itens'    => self::getEquipamentosItens($request)
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
            'title'    => 'Cadastrar Novo Equipamento',
            'botao'    => 'Voltar', 
            'type_btn' => 'btn btn-secondary btn-icon-split' 
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
        $id_user = $_SESSION['admin']['usuario']['id'];

        //NOVA INSTÂNCIA DE DEPOIMENTO
        $obEquipamentos = new EntityEquipamentos;
        $obEquipamentos->id_user    = $id_user;
        $obEquipamentos->patrimonio = $postVars['patrimonio'];
        $obEquipamentos->nome       = $postVars['nome'];
        $obEquipamentos->descricao  = $postVars['descricao'];
        $obEquipamentos->local      = $postVars['local'];
        $obEquipamentos->imagem     = $postVars['imagem'];
        $obEquipamentos->horas      = $postVars['horas'];
        $obEquipamentos->status     = $postVars['status'];
        $obEquipamentos->hist_manu   = $postVars['hist_manu'];
        $obEquipamentos->cadastrar();

        return self::getEquipamentos();
    }
}