<?php

namespace App\Controller\Admin;

use \DateTime;
use \App\Utils\View;
use \App\Model\Entity\Agendamentos as EntityAgendamentos;
use \App\Model\Entity\Equipamentos as EntityEquipamentos;
use \App\Model\Entity\Responsaveis as EntityResponsaveis;

class Agendamentos extends Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO (VIEW) DA PAGINA AGENDAMENTOS
     * @param Request $request
     * @return string 
    */
    public static function getAgendamentos($request){
        //CONTEÚDO DA PÁGINA 
        $content = View::render('Admin/agendamentos/agendamentos', [
            'title'    => 'Agenda de manutenções',
            'botao'    => 'Agendar Manutenções',
            'type_btn' => 'btn btn-primary btn-icon-split',
            'itens'    => self::getAgendamentosItens($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Agendamentos', $content, 'Agendamentos', $request);
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR O FORMULÁRIO DE CADASTRO DE UM NOVO AGENDAMENTO
     * @param Request $request
     * @return string
     */
    public static function getNewAgendamento($request){
        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('Admin/agendamentos/form', [
            'title'         => 'Novo Agendamento',
            'titulo'        => '',
            'equip'         => self::getEquipamentosItens($request, ''),
            'tipo0'         => 'checked',
            'tipo1'         => '',
            'tipo2'         => '',
            'tipo3'         => '',
            'tipo4'         => '',
            'tipo5'         => '',
            'tipo6'         => '',
            'outro_display' => 'style="display: none;"',
            'outro_text'    => '',
            'dt_st'         => '',
            'dt_fs'         => '',
            'replace0'      => 'checked',
            'replace1'      => '',
            'replace2'      => '',
            'replace3'      => '',
            'replace4'      => '',
            'dia_text'      => '',
            'dia_display'   => 'style="display: none;"',
            'sem_text'      => '',
            'sem_display'   => 'style="display: none;"',
            'mes_text'      => '',
            'mes_display'   => 'style="display: none;"',
            'ano_text'      => '',
            'ano_display'   => 'style="display: none;"',
            'dom'           => '',
            'seg'           => '',
            'ter'           => '',
            'qua'           => '',
            'qui'           => '',
            'sex'           => '',
            'sab'           => '',
            'duracao0'      => 'checked',
            'duracao1'      => '',
            'duracao2'      => '',
            'dur_display'   => 'style="display: none;"',
            'num_display'   => 'style="display: none;"',
            'dt_ate_display'=> 'style="display: none;"',
            'num_text'      => '',
            'date_ate_text' => '',
            'descricao'     => '',
            'insp0'         => 'checked',
            'insp1'         => '',
            'respons'       => self::getResponsaveisItens($request),
            'alert0'        => '',
            'alert1'        => '',
            'alert2'        => '',
            'alert_text0'   => '',
            'alert_text1'   => '',
            'alert_text2'   => '',
            'alert_display0'=> 'style="display: none;"',
            'alert_display1'=> 'style="display: none;"',
            'alert_display2'=> 'style="display: none;"',
            'botao1'    => 'Voltar', 
            'type_btn1' => 'btn btn-secondary btn-icon-split',
            'botao2'    => 'Agendar', 
            'type_btn2' => 'btn btn-success btn-icon-split' ,
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Agendamento', $content, 'Agendamentos', $request);
    }

    /**
     * MÉTODO RESPONSÁVEL EM OBTER A RENDERIZAÇÃO DOS ITENS DOS RESPONSAVEIS PARA A PÁGINA
     * @param Request $request
     * @return string
     */
    private static function getResponsaveisItens($request, $resp = []){
        //ITENS
        $itens  = '';

        //PEGA O ID DO USUÁRIO PELA SESSÃO
        $id_user = $_SESSION['admin']['usuario']['id'];

        //RESULTADOS DA PÁGINA
        $results = EntityResponsaveis::getResponsaveis('id_user ='. $id_user, 'id DESC', NULL);
        
        //RENDERIZA CADA RESPONSÁVEL
        while($obResponsaveis = $results->fetchObject(EntityResponsaveis::class)){
            if(in_array($obResponsaveis->id, $resp)){
                $select = 'selected';
            }else{
                $select = '';
            }
            $itens .= View::render('Admin/agendamentos/option', [
                'id'         => $obResponsaveis->id,        
                'value'      => $obResponsaveis->nome,
                'selected'   => $select,
            ]);
        }

        //RETORNA OS RESPONSÁVEIS
        return $itens;
    }

    /**
     * MÉTODO RESPONSÁVEL EM OBTER A RENDERIZAÇÃO DOS ITENS DOS EQUIPAMENTOS PARA A PÁGINA
     * @param Request $request
     * @return string
     */
    private static function getEquipamentosItens($request, $id_eq){
        //ITENS
        $itens  = '';

        //PEGA O ID DO USUÁRIO PELA SESSÃO
        $id_user = $_SESSION['admin']['usuario']['id'];

        //RESULTADOS DA PÁGINA
        $results = EntityEquipamentos::getEquipamentos('id_user ='. $id_user, 'id DESC', NULL);

        //RENDERIZA CADA EQUIPAMENTO
        while($obEquipamentos = $results->fetchObject(EntityEquipamentos::class)){
            if($id_eq == $obEquipamentos->id){
                $select = 'selected';
            }else{
                $select = '';
            }
            $itens .= View::render('Admin/agendamentos/option', [
                'id'         => $obEquipamentos->id,        
                'value'      => $obEquipamentos->patrimonio.' - '.$obEquipamentos->nome,
                'selected'   => $select,
            ]);
        }

        //RETORNA OS EQUIPAMENTOS
        return $itens;
    }

    /**
     * MÉTODO RESPONSÁVEL EM OBTER A RENDERIZAÇÃO DOS ITENS DOS AGENDAMENTOS PARA A PÁGINA
     * @param Request $request
     * @return string
     */
    private static function getAgendamentosItens($request){
        //ITENS
        $itens  = '';

        //PEGA O ID DO USUÁRIO PELA SESSÃO
        $id_user = $_SESSION['admin']['usuario']['id'];

        //RESULTADOS DA PÁGINA
        $results = EntityAgendamentos::getAgendamentos('id_user ='. $id_user, 'id DESC', NULL);

        //RENDERIZA CADA AGENDAMENTO
        while($obAgendamentos = $results->fetchObject(EntityAgendamentos::class)){
            //VERIFICA SE O EQUIPAMENTO NÃO É NULO
            if($obAgendamentos->equipamento != ''){
                $eq = ' - '.explode(',', $obAgendamentos->equipamento)[1];
            }else{
                $eq = '';
            }
            if($obAgendamentos->status != 'success'){
                $env_display = 'style="display: none;"';
            }else{
                $env_display = '';
            }
            $itens .= View::render('Admin/agendamentos/itens', [
                'id'         => $obAgendamentos->id,        
                'title'      => $obAgendamentos->title.$eq,
                'status'     => $obAgendamentos->status,
                'env_display'=> $env_display
            ]);
        }

        //RETORNA OS EQUIPAMENTOS
        return $itens;
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR CRIAR UM NOVO AGENDAMENTO
     * @param Request $request
     * @return string
     */
    public static function setNewAgendamento($request){
        //DADOS DO POST
        $postVars = $request->getPostVars();

        //TIPO DE MANUTENÇÃO
        $tipos = self::getTipoManutencao($postVars['tipo'], $postVars['outro']);
        $tipo = $postVars['tipo'].','.$tipos;

        //OBTÉM O EQUIPAMENTO DO BANCO DE DADOS
        $obEquipamento = EntityEquipamentos::getEquip(strval($postVars['equipamento']));
        $equipamento = $postVars['equipamento'].','.$obEquipamento->nome;

        //VERIFICAÇÕES DO POST
        $dia = $postVars['dia'] ?? '';
        $sem = $postVars['semana'] ?? '';
        $mes = $postVars['mes'] ?? '';
        $ano = $postVars['ano'] ?? '';
        $dom = $postVars['dom'] ?? '';
        $seg = $postVars['seg'] ?? '';
        $ter = $postVars['ter'] ?? '';
        $qua = $postVars['qua'] ?? '';
        $qui = $postVars['qui'] ?? '';
        $sex = $postVars['sex'] ?? '';
        $sab = $postVars['sab'] ?? '';
        $replace = $postVars['replace'] ?? '';
        $duracao = $postVars['duracao'] ?? '';
        $count = $postVars['num'] ?? '';
        $until = $postVars['data-ate-fs'] ?? '';
        if($dia == ''){ $dia = '1';}
        if($sem == ''){ $sem = '1';}
        if($mes == ''){ $mes = '1';}
        if($ano == ''){ $ano = '1';}
        if($duracao == ''){ $duracao = '10';}

        //REPETIÇÕES
        $frequecia = $replace.','.$duracao.','.$count.','.$until.','.$dia.','.$sem.','.$mes.','.$ano.','.$dom.','.$seg.','.$ter.','.$qua.','.$qui.','.$sex.','.$sab;

        //ALERTAS
        $alertas = $postVars['alert0'].','.$postVars['alert1'].','.$postVars['alert2'];

        //CRIA NOVA INSTÂNCIA DE AGENDAMENTO
        $obAgendamento = new EntityAgendamentos();
        $obAgendamento->id_user         = strval($_SESSION['admin']['usuario']['id']);
        $obAgendamento->equipamento     = $equipamento ?? NULL;
        $obAgendamento->responsaveis    = $postVars['resp'] ?? '';
        $obAgendamento->title           = $postVars['titulo'] ?? '';
        $obAgendamento->dt_st           = $postVars['data-st'] ?? '';
        $obAgendamento->dt_fs           = $postVars['data-fs'] ?? '';
        $obAgendamento->freq            = $frequecia ?? '';
        $obAgendamento->alert           = $alertas ?? '';
        $obAgendamento->tipo            = $tipo ?? '';
        $obAgendamento->inspecao        = strval($postVars['inspecao']);
        $obAgendamento->descricao       = $postVars['descricao'] ?? '';
        $obAgendamento->status          = 'success'; //COLORS STATUS: success=VERDE|primary=AZUL|warning=AMARELO|info=CIANO|danger=VERMELHO

        //CADASTRA O AGENDAMENTO NO BANCO DE DADOS
        $obAgendamento->cadastrar();

        //REDIRECIONA O USUÁRIO PARA A PAGE AGENDAMENTOS
        $request->getRouter()->redirect('/new-agendamento?status=agended');
    }
    
    /**
     * MÉTODO RESPONSÁVEL E DEFINIR O TIPO DE MANUTENÇÃO
     * @param string $idt
     * @param string $outro
     * @return tipodemanutencao
     */
    public static function getTipoManutencao($idt, $outro){
        if(isset($idt)){
            switch ($idt) {
                case '0':
                    return 'corretiva';
                    break;
                case '1':
                    return 'preventiva';
                    break;
                case '2':
                    return 'periodica';
                    break;
                case '3':
                    return 'limpeza';
                    break;
                case '4':
                    return 'troca de oleo';
                    break;
                case '5':
                    return 'troca de peca';
                    break;
                case '6':
                    return $outro;
                    break;
                default:
                    return '';
                    break;
            }
        }
    }

    /**
     * MÉTODO RESPONSÁVEL POR EXCLUIR UM AGENDAMENTO
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setDeleteAgendamento($request, $id){
        //OBTÉM O AGENDAMENTO DO BANCO DE DADOS
        $obAgendamento = EntityAgendamentos::getAgend($id);

        //VALIDA A INSTANCIA
        if(!$obAgendamento instanceof EntityAgendamentos){
            $request->getRouter()->redirect('/agendamentos');
        }

        //EXCLUI O AGENDAMENTO
        $obAgendamento->excluir();
        
        //REDIRECIONA O USUÁRIO PARA A PAGE AGENDAMENTOS
        $request->getRouter()->redirect('/agendamentos?status=deleted');
    }

    /**
     * MÉTODO RESPONSÁVEL POR RENDERIZAR A VIEW DO EDIÇÃO DE UM AGENDAMENTO
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function getEditAgendamento($request, $id){
        //OBTÉM O AGENDAMENTO DO BANCO DE DADOS
        $obAgendamento = EntityAgendamentos::getAgend($id);

        //VALIDA A INSTANCIA
        if(!$obAgendamento instanceof EntityAgendamentos){
            $request->getRouter()->redirect('/agendamentos');
        }

        //TIPOS DE MANUTENÇÃO
        $tipo = explode(',',$obAgendamento->tipo)[0];
        if($tipo == '0'){$tipo0 = 'checked';}else{$tipo0 = '';}
        if($tipo == '1'){$tipo1 = 'checked';}else{$tipo1 = '';}
        if($tipo == '2'){$tipo2 = 'checked';}else{$tipo2 = '';}
        if($tipo == '3'){$tipo3 = 'checked';}else{$tipo3 = '';}
        if($tipo == '4'){$tipo4 = 'checked';}else{$tipo4 = '';}
        if($tipo == '5'){$tipo5 = 'checked';}else{$tipo5 = '';}
        if($tipo == '6'){$tipo6 = 'checked'; $outro_display = ''; $outro_text = explode(',',$obAgendamento->tipo)[1];}else{$tipo6 = ''; $outro_display = 'style="display: none;"'; $outro_text = '';}

        //FREQUENCIAS DE REPETIÇOES
        $freq = explode(',',$obAgendamento->freq);
        $replace = $freq[0];
        if($replace == '0'){$replace0 = 'checked'; $dur_display = 'style="display: none;"';}else{$replace0 = ''; $dur_display = '';}
        if($replace == '1'){$replace1 = 'checked'; $dia_display = ''; $dia_text = $freq[4];}else{$replace1 = ''; $dia_display = 'style="display: none;"'; $dia_text = '';}
        if($replace == '2'){
            $replace2 = 'checked'; 
            $sem_display = ''; 
            $sem_text = $freq[5];
            if($freq[8]!=''){$dom = 'checked';}else{$dom = '';}
            if($freq[9]!=''){$seg = 'checked';}else{$seg = '';}
            if($freq[10]!=''){$ter = 'checked';}else{$ter = '';}
            if($freq[11]!=''){$qua = 'checked';}else{$qua = '';}
            if($freq[12]!=''){$qui = 'checked';}else{$qui = '';}
            if($freq[13]!=''){$sex = 'checked';}else{$sex = '';}
            if($freq[14]!=''){$sab = 'checked';}else{$sab = '';}
        }else{
            $replace2 = ''; 
            $sem_display = 'style="display: none;"'; 
            $sem_text = '';
            $dom = '';
            $seg = '';
            $ter = '';
            $qua = '';
            $qui = '';
            $sex = '';
            $sab = '';
        }
        if($replace == '3'){$replace3 = 'checked'; $mes_display = ''; $mes_text = $freq[6];}else{$replace3 = ''; $mes_display = 'style="display: none;"'; $mes_text = '';}
        if($replace == '4'){$replace4 = 'checked'; $ano_display = ''; $ano_text = $freq[7];}else{$replace4 = ''; $ano_display = 'style="display: none;"'; $ano_text = '';}
        
        //DURAÇÃO DOS AGENDAMENTOS
        $duration = $freq[1];
        if($duration == '0'){$duracao0 = 'checked';}else{$duracao0 = '';}
        if($duration == '1'){$duracao1 = 'checked'; $num_display = '';}else{$duracao1 = ''; $num_display = 'style="display: none;"';}
        if($duration == '2'){$duracao2 = 'checked'; $dt_ate_display = '';}else{$duracao2 = ''; $dt_ate_display = 'style="display: none;"';}

        //INSPECÇÃO
        $isnpecao = $obAgendamento->inspecao;
        if($isnpecao == '0'){$insp0 = 'checked'; $insp1 = '';}else{$insp0 = ''; $insp1 = 'checked';}

        //ARRAY COM O ID DOS RESPONSÁVEIS
        $resp = explode(',',$obAgendamento->responsaveis);

        //ALERTAS
        $alert = explode(',',$obAgendamento->alert);
        if($alert[0] != ''){$alert0 = 'checked'; $alert_text0 = $alert[0]; $alert_display0 = '';}else{$alert0 = ''; $alert_text0 = ''; $alert_display0 = 'style="display: none;"';}
        if($alert[1] != ''){$alert1 = 'checked'; $alert_text1 = $alert[1]; $alert_display1 = '';}else{$alert1 = ''; $alert_text1 = ''; $alert_display1 = 'style="display: none;"';}
        if($alert[2] != ''){$alert2 = 'checked'; $alert_text2 = $alert[2]; $alert_display2 = '';}else{$alert2 = ''; $alert_text2 = ''; $alert_display2 = 'style="display: none;"';}

        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('Admin/agendamentos/form', [
            'title'         => 'Editar Agendamento',
            'titulo'        => $obAgendamento->title,
            'equip'         => self::getEquipamentosItens($request, explode(',',$obAgendamento->equipamento)[0]),
            'tipo0'         => $tipo0,
            'tipo1'         => $tipo1,
            'tipo2'         => $tipo2,
            'tipo3'         => $tipo3,
            'tipo4'         => $tipo4,
            'tipo5'         => $tipo5,
            'tipo6'         => $tipo6,
            'outro_display' => $outro_display,
            'outro_text'    => $outro_text,
            'dt_st'         => $obAgendamento->dt_st,
            'dt_fs'         => $obAgendamento->dt_fs,
            'replace0'      => $replace0,
            'replace1'      => $replace1,
            'replace2'      => $replace2,
            'replace3'      => $replace3,
            'replace4'      => $replace4,
            'dia_text'      => $dia_text,
            'dia_display'   => $dia_display,
            'sem_text'      => $sem_text,
            'sem_display'   => $sem_display,
            'mes_text'      => $mes_text,
            'mes_display'   => $mes_display,
            'ano_text'      => $ano_text,
            'ano_display'   => $ano_display,
            'dom'           => $dom,
            'seg'           => $seg,
            'ter'           => $ter,
            'qua'           => $qua,
            'qui'           => $qui,
            'sex'           => $sex,
            'sab'           => $sab,
            'duracao0'      => $duracao0,
            'duracao1'      => $duracao1,
            'duracao2'      => $duracao2,
            'dur_display'   => $dur_display,
            'num_display'   => $num_display,
            'dt_ate_display'=> $dt_ate_display,
            'num_text'      => $freq[2],
            'date_ate_text' => $freq[3],
            'descricao'     => $obAgendamento->descricao,
            'insp0'         => $insp0,
            'insp1'         => $insp1,
            'respons'       => self::getResponsaveisItens($request, $resp),
            'alert0'        => $alert0,
            'alert1'        => $alert1,
            'alert2'        => $alert2,
            'alert_text0'   => $alert_text0,
            'alert_text1'   => $alert_text1,
            'alert_text2'   => $alert_text2,
            'alert_display0'=> $alert_display0,
            'alert_display1'=> $alert_display1,
            'alert_display2'=> $alert_display2,
            'botao1'        => 'Voltar', 
            'type_btn1'     => 'btn btn-secondary btn-icon-split',
            'botao2'        => 'Atualizar', 
            'type_btn2'     => 'btn btn-success btn-icon-split' ,
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('MANUUFRB - Editar Agendamento', $content, 'Agendamentos', $request);
    }

    /**
     * MÉTODO RESPONSÁVEL POR EDITAR UM AGENDAMENTO (POST)
     * @param Request $request
     * @return string
     */
    public static function setEditAgendamento($request, $id){
        //OBTÉM O AGENDAMENTO DO BANCO DE DADOS
        $obAgendamento = EntityAgendamentos::getAgend($id);

        //VALIDA A INSTANCIA
        if(!$obAgendamento instanceof EntityAgendamentos){
            $request->getRouter()->redirect('/agendamentos');
        }

        //DADOS DO POST
        $postVars = $request->getPostVars();

        //TIPO DE MANUTENÇÃO
        $tipos = self::getTipoManutencao($postVars['tipo'], $postVars['outro']);
        $tipo = $postVars['tipo'].','.$tipos;

        //OBTÉM O EQUIPAMENTO DO BANCO DE DADOS
        $obEquipamento = EntityEquipamentos::getEquip(strval($postVars['equipamento']));
        $equipamento = $postVars['equipamento'].','.$obEquipamento->nome;

        //VERIFICAÇÕES DO POST
        $dia = $postVars['dia'] ?? '';
        $sem = $postVars['semana'] ?? '';
        $mes = $postVars['mes'] ?? '';
        $ano = $postVars['ano'] ?? '';
        $dom = $postVars['dom'] ?? '';
        $seg = $postVars['seg'] ?? '';
        $ter = $postVars['ter'] ?? '';
        $qua = $postVars['qua'] ?? '';
        $qui = $postVars['qui'] ?? '';
        $sex = $postVars['sex'] ?? '';
        $sab = $postVars['sab'] ?? '';
        $replace = $postVars['replace'] ?? '';
        $duracao = $postVars['duracao'] ?? '';
        $count = $postVars['num'] ?? '';
        $until = $postVars['data-ate-fs'] ?? '';
        if($dia == ''){ $dia = '1';}
        if($sem == ''){ $sem = '1';}
        if($mes == ''){ $mes = '1';}
        if($ano == ''){ $ano = '1';}
        if($duracao == ''){ $duracao = '10';}

        //REPETIÇÕES
        $frequecia = $replace.','.$duracao.','.$count.','.$until.','.$dia.','.$sem.','.$mes.','.$ano.','.$dom.','.$seg.','.$ter.','.$qua.','.$qui.','.$sex.','.$sab;

        //ALERTAS
        $alertas = $postVars['alert0'].','.$postVars['alert1'].','.$postVars['alert2'];

        //ATUALIZA A INSTÂNCIA DO AGENDAMENTO
        $obAgendamento->id              = $id;
        $obAgendamento->id_user         = strval($_SESSION['admin']['usuario']['id']);
        $obAgendamento->equipamento     = $equipamento ?? NULL;
        $obAgendamento->responsaveis    = $postVars['resp'] ?? '';
        $obAgendamento->title           = $postVars['titulo'] ?? '';
        $obAgendamento->dt_st           = $postVars['data-st'] ?? '';
        $obAgendamento->dt_fs           = $postVars['data-fs'] ?? '';
        $obAgendamento->freq            = $frequecia ?? '';
        $obAgendamento->alert           = $alertas ?? '';
        $obAgendamento->tipo            = $tipo ?? '';
        $obAgendamento->inspecao        = strval($postVars['inspecao']);
        $obAgendamento->descricao       = $postVars['descricao'] ?? '';
        $obAgendamento->status          = 'success'; //COLORS STATUS: success=VERDE|primary=AZUL|warning=AMARELO|info=CIANO|danger=VERMELHO

        //ATUALIZA O AGENDAMENTO NO BANCO DE DADOS
        $obAgendamento->atualizar();

        //REDIRECIONA O USUÁRIO PARA A PAGE AGENDAMENTOS
        $request->getRouter()->redirect('/agendamentos?status=edited');
    }
}