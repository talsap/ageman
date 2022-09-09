<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Equipamentos as EntityEquipamentos;
use \App\Model\Entity\Locais as EntityLocal;
use \App\Model\Entity\Areas;
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
                'area'       => $obEquipamentos->area,
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
            'title'    => 'Cadastrar um Novo Equipamento',
            'patrimonio'=> '',
            'nome'      => '',
            'local'     => self::getLocalItens($request),
            'area'      => '',
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

        //PEGA O ID DA ÁREA
        $id_area = $postVars['eq-area'];

        //OBTÉM A INSTÂNCIA DO LOCAL NO BANCO DE DADOS
        $obLocal = EntityLocal::getLocal($id_area);

        //CRIA A VARIÁVEL LOCAL APARTIR DA INSTÂNCIA
        $local = $obLocal->local;

        //CRIA A VARIÁVEL AREA APARTIR DA INSTÂNCIA
        $area = $obLocal->area;

        //CRIA NOVA INSTÂNCIA DO EQUIPAMENTO
        $obEquipamentos = new EntityEquipamentos();
        $obEquipamentos->id_user    = strval($_SESSION['admin']['usuario']['id']);
        $obEquipamentos->patrimonio = $postVars['patrimonio'] ?? '';
        $obEquipamentos->nome       = $postVars['nome'] ?? '';
        $obEquipamentos->descricao  = $postVars['descricao'] ?? '';
        $obEquipamentos->local      = $local ?? '';
        $obEquipamentos->area       = $area ?? '';
        $obEquipamentos->imagem     = $dir_image ?? '';
        $obEquipamentos->horas      = $postVars['horas'] ?? '';
        $obEquipamentos->status     = $postVars['status'] ?? '';
        $obEquipamentos->hist_manu  = $postVars['hist_manu'] ?? '';
        
        //CADASTRA O EQUIPAMENTO NO BANCO DE DADOS
        $obEquipamentos->cadastrar();
        
        //REDIRECIONA O USUÁRIO PARA A PAGE EQUIPAMENTOS
        $request->getRouter()->redirect('/new-equipamento?status=created');
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
            'local'     => self::getLocalItensSelector($request, $obEquipamento->local),
            'area'      => self::getAreaItensSelector($request, $obEquipamento->local, $obEquipamento->area),
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

        //PEGA O ID DA ÁREA
        $id_area = $postVars['eq-area'];

        //OBTÉM A INSTÂNCIA DO LOCAL NO BANCO DE DADOS
        $obLocal = EntityLocal::getLocal($id_area);

        //CRIA A VARIÁVEL LOCAL APARTIR DA INSTÂNCIA
        $local = $obLocal->local;

        //CRIA A VARIÁVEL AREA APARTIR DA INSTÂNCIA
        $area = $obLocal->area;

        //VERIFICA SE O NOME, O PATRIMONIO, O LACAL E A AREA DO EQUIPAMENTO MUDOU
        if($obEquipamento->patrimonio != $postVars['patrimonio'] or $obEquipamento->nome != $postVars['nome']){
            $condition = true;
        }else{$condition = false;}
        
        //ATUALIZA A INSTÂNCIA DO EQUIPAMENTO
        $obEquipamento->id         = $id;
        $obEquipamento->id_user    = strval($_SESSION['admin']['usuario']['id']);
        $obEquipamento->patrimonio = $postVars['patrimonio'] ?? $obEquipamento->patrimonio;
        $obEquipamento->nome       = $postVars['nome'] ?? $obEquipamento->nome;
        $obEquipamento->descricao  = $postVars['descricao'] ?? $obEquipamento->descricao ;
        $obEquipamento->local      = $local ?? $obEquipamento->local;
        $obEquipamento->area       = $area ?? $obEquipamento->area;
        $obEquipamento->imagem     = $dir_image ?? $obEquipamento->imagem ;
        $obEquipamento->horas      = $postVars['horas'] ?? $obEquipamento->horas;
        $obEquipamento->status     = $postVars['status'] ?? $obEquipamento->status;
        $obEquipamento->hist_manu  = $postVars['hist_manu'] ?? $obEquipamento->hist_manu;
        
        //ATUALIZA O EQUIPAMENTO NO BANCO DE DADOS
        if($condition){
            $obEquipamento->atualizarAmpla();
        }else{
            $obEquipamento->atualizar();
        }
        
        //REDIRECIONA O USUÁRIO PARA A PAGE EQUIPAMENTOS
        $request->getRouter()->redirect('/equipamentos?status=edited');
    }

    /**
     * MÉTODO RESPONSÁVEL POR EXCLUIR UM EQUIPAMENTO
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setDeleteEquipamento($request, $id){
        //OBTÉM O EQUIPAMENTO DO BANCO DE DADOS
        $obEquipamento = EntityEquipamentos::getEquip($id);

        //VALIDA A INSTANCIA
        if(!$obEquipamento instanceof EntityEquipamentos){
            $request->getRouter()->redirect('/equipamentos');
        }

        //EXCLUI A IMAGEM ANTERIOR SE ELA EXISTIR
        if(file_exists($obEquipamento->imagem)){
            unlink($obEquipamento->imagem);
        }

        //EXCLUI O EQUIPAMENTO
        $obEquipamento->excluir();
        
        //REDIRECIONA O USUÁRIO PARA A PAGE EQUIPAMENTOS
        $request->getRouter()->redirect('/equipamentos?status=deleted');
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
            $itens .= View::render('Admin/equipamentos/option', [
                'id'         => $obLocal->id,        
                'value'      => $obLocal->local,
                'selected'   => '',
            ]);
        }

        //RETORNA OS LOCAIS
        return $itens;
    }

    /**
     * MÉTODO RESPONSÁVEL PRO OBTER A RENDERIZAÇÃO DO SELECT DOS LOCAIS COM O SELECTOR
     * @param Request $request
     * @param string $lc
     * @return string
     */
    private static function getLocalItensSelector($request, $lc){
        //ITENS
        $itens  = '';

        //PEGA O ID DO USUÁRIO PELA SESSÃO
        $id_user = $_SESSION['admin']['usuario']['id'];

        //RESULTADOS DA PÁGINA
        $results = EntityLocal::getLocais('id_user = '.$id_user.' '.'AND area = ""', 'id DESC', NULL);

        //RENDERIZA CADA LOCAL
        while($obLocal = $results->fetchObject(EntityLocal::class)){
            if($lc == $obLocal->local) {
                $select = 'selected';
            }else{
                $select = '';
            }
            $itens .= View::render('Admin/equipamentos/option', [
                'id'         => $obLocal->id,        
                'value'      => $obLocal->local,
                'selected'   => $select,
            ]);
        }

        //RETORNA OS LOCAIS
        return $itens;
    }

    /**
     * MÉTODO RESPONSÁVEL PRO OBTER A RENDERIZAÇÃO DO SELECT DAS ÁREAS COM O SELECTOR
     * @param Request $request
     * @return string
     */
    private static function getAreaItensSelector($request, $lc, $ar){
        //ITENS
        $itens  = '';

        //PEGA O ID DO USUÁRIO PELA SESSÃO
        $id_user = $_SESSION['admin']['usuario']['id'];

        //RESULTADOS DA PÁGINA
        $results = EntityLocal::getLocais('id_user = '.$id_user.' '.'AND local = "'.$lc.'"', 'id DESC', NULL);

        //RENDERIZA CADA AREA
        while($obLocal = $results->fetchObject(EntityLocal::class)){
            if($ar == $obLocal->area) {
                $select = 'selected';
            }else{
                $select = '';
            }
            if($obLocal->area != ''){
                $itens .= View::render('Admin/equipamentos/option', [
                    'id'         => $obLocal->id,        
                    'value'      => $obLocal->area,
                    'selected'   => $select,
                ]);
            }
        }

        //RETORNA AS ÁREAS
        return $itens;
    }

}
