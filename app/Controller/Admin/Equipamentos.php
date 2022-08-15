<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Equipamentos as EntityEquipamentos;
use \App\File\Uploud;

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
        return parent::getPanel('MANUUFRB - Equipamentos', $content, 'Equipamentos', $request);
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
            'patrimonio'=> '',
            'nome'      => '',
            'local'     => '',
            'descricao' => '',
            'botao1'    => 'Voltar', 
            'type_btn1' => 'btn btn-secondary btn-icon-split',
            'botao2'    => 'Cadastrar', 
            'type_btn2' => 'btn btn-success btn-icon-split' ,
            'link'      => '' //link = {{src="resources/img/uplouds/User1/equipamentos/01.png"}}
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Cadastrar Equipamento', $content, 'Equipamentos', $request);
    }

    /**
     * MÉTODO RESPONSÁVEL POR CADASTRAR UM EQUIPAMENTO
     * @param Request $request
     * @return string
     */
    public static function setNewEquipamento($request){
        //CRIA UM LOCAL PARA O USUÁRIO
        $locale = 'User'.$_SESSION['admin']['usuario']['id'];
        
        //DIRETÓRIO DE ARQUIVOS DO USUÁRIO 
        $dir = 'resources/img/uplouds/'.$locale.'/equipamentos';

        //VERIFICA SE JÁ EXITE O DIRETÓRIO DE ARQUIVOS DO USUÁRIO
        if(!is_dir($dir)){
            mkdir($dir, 0755, true);
        }

        //VERIFICA SE EXISTE UM ARQUIVO
        if(isset($_FILES['image'])){
            //GERA O OBJETO DO ARQUIVO DE UPLOUD
            $obUploud = new Uploud($_FILES['image']);

            //VERIFICA A EXTENSAO DO ARQUIVO
            $extension = $obUploud->getExtension();
            $sucesso = $obUploud->verificaExtension($extension, array("jpg", "png", "jpeg"));
            if($sucesso){

                //VERIFICA SE O TAMANHO É MENOR QUE 2MB
                $size = $obUploud->getSize();
                if($size <= 2097152){ 

                    //FAZ O UPLOUD DA IMAGEM
                    $dir_image = $obUploud->uploud($dir, false);
                }
            }
        }
        
        //DADOS DO POST
        $postVars = $request->getPostVars();

        //INSTÂNCIA DO EQUIPAMENTO
        $obEquipamentos = new EntityEquipamentos();
        $obEquipamentos->id_user    = strval($_SESSION['admin']['usuario']['id']);
        $obEquipamentos->patrimonio = $postVars['patrimonio'];
        $obEquipamentos->nome       = $postVars['nome'];
        $obEquipamentos->descricao  = $postVars['descricao'];
        $obEquipamentos->local      = $postVars['local'];
        $obEquipamentos->imagem     = $dir_image ?? '';
        $obEquipamentos->horas      = $postVars['horas'] ?? '';
        $obEquipamentos->status     = $postVars['status'] ?? '';
        $obEquipamentos->hist_manu  = $postVars['hist_manu'] ?? '';
        
        //CADASTRA O EQUIPAMENTO NO BANCO DE DADOS
        $obEquipamentos->cadastrar();
        
        //REDIRECIONA O USUÁRIO PARA A PAGE EQUIPAMENTOS
        $request->getRouter()->redirect('/new-equipamentos?status=created');
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR O FORMULÁRIO DE EDIÇÃO DE UM EQUIPAMENTO
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function getEditEquipamento($request, $id){
        //OBTÉM O EQUIPAMENTO DO BANCO DE DADOS
        $obEquipamento = EntityEquipamentos::getEquip($id);

        //VALIDA A INSTANCIA
        if(!$obEquipamento instanceof EntityEquipamentos){
            $request->getRouter()->redirect('/equipamentos');
        }

        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('Admin/equipamentos/form', [
            'title'     => 'Editar Equipamento',
            'patrimonio'=> $obEquipamento->patrimonio,
            'nome'      => $obEquipamento->nome,
            'local'     => $obEquipamento->local,
            'descricao' => $obEquipamento->descricao,
            'botao1'    => 'Voltar', 
            'type_btn1' => 'btn btn-secondary btn-icon-split',
            'botao2'    => 'Alterar', 
            'type_btn2' => 'btn btn-success btn-icon-split',
            'link'      => 'src="'.$obEquipamento->imagem.'"',
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Editar Equipamento', $content, 'Equipamentos', $request);

    }

    /**
     * MÉTODO RESPONSÁVEL GRAVAR A ATUALIZAÇAO DE EDIÇÃO DE UM EQUIPAMENTO
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setEditEquipamento($request, $id){
        //OBTÉM O EQUIPAMENTO DO BANCO DE DADOS
        $obEquipamento = EntityEquipamentos::getEquip($id);

        //VALIDA A INSTANCIA
        if(!$obEquipamento instanceof EntityEquipamentos){
            $request->getRouter()->redirect('/equipamentos');
        }

        //CRIA UM LOCAL PARA O USUÁRIO
        $locale = 'User'.$_SESSION['admin']['usuario']['id'];
        
        //DIRETÓRIO DE ARQUIVOS DO USUÁRIO 
        $dir = 'resources/img/uplouds/'.$locale.'/equipamentos';

        //VERIFICA SE JÁ EXITE O DIRETÓRIO CASO CONTRÁRIO CRIA O DIRETÓRIO
        if(!is_dir($dir)){
            mkdir($dir, 0755, true);
        }

        //VERIFICA SE EXISTE UM ARQUIVO
        if(isset($_FILES['image'])){
            //GERA O OBJETO DO ARQUIVO DE UPLOUD
            $obUploud = new Uploud($_FILES['image']);

            //VERIFICA A EXTENSAO DO ARQUIVO
            $extension = $obUploud->getExtension();
            $sucesso = $obUploud->verificaExtension($extension, array("jpg", "png", "jpeg"));
            if($sucesso){

                //VERIFICA SE O TAMANHO É MENOR QUE 2MB
                $size = $obUploud->getSize();
                if($size <= 2097152){ 

                    //EXCLUI A IMAGEM ANTERIOR SE ELA EXISTIR
                    if(file_exists($obEquipamento->imagem)){
                        unlink($obEquipamento->imagem);
                    }
                    
                    //FAZ O UPLOUD DA NOVA IMAGEM
                    $dir_image = $obUploud->uploud($dir, false);
 
                }
            }
        }

        //POST VARS
        $postVars = $request->getPostVars();

        //ATUALIZA A INSTÂNCIA DO EQUIPAMENTO
        $obEquipamento->id         = $id;
        $obEquipamento->id_user    = strval($_SESSION['admin']['usuario']['id']);
        $obEquipamento->patrimonio = $postVars['patrimonio'] ?? $obEquipamento->patrimonio;
        $obEquipamento->nome       = $postVars['nome'] ?? $obEquipamento->nome;
        $obEquipamento->descricao  = $postVars['descricao'] ?? $obEquipamento->descricao ;
        $obEquipamento->local      = $postVars['local'] ?? $obEquipamento->local;
        $obEquipamento->imagem     = $dir_image ?? $obEquipamento->imagem ;
        $obEquipamento->horas      = $postVars['horas'] ?? $obEquipamento->horas;
        $obEquipamento->status     = $postVars['status'] ?? $obEquipamento->status;
        $obEquipamento->hist_manu  = $postVars['hist_manu'] ?? $obEquipamento->hist_manu;
        
        //ATUALIZA O EQUIPAMENTO NO BANCO DE DADOS
        $obEquipamento->atualizar();
        
        //REDIRECIONA O USUÁRIO PARA A PAGE EQUIPAMENTOS
        $request->getRouter()->redirect('/equipamentos?status=edited');
    }
}
