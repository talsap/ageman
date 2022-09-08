<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class Agendamentos{

    /**
     * ID DO AGENDAMENTO
     * @var integer
     */
    public $id;

    /**
     * ID DO USUÁRIO LOGADO
     * @var integer
     */
    public $id_user;
    
    /**
     * TÍTULO DO AGENDAMENTO
     * @var string
     */
    public $title;

    /**
     * ID DO EQUIPAMENTO
     * @var integer
     */
    public $id_equipamento;

    /**
     * ID DOS RESPONSÁVEIS CONCATENADO
     * @var string
     */
    public $id_responsaveis;

    /**
     * DATA DE ÍNICIO DO AGENDAMENTO
     * @var string
     */
    public $dt_st;

    /**
     * DATA FINAL DO AGENDAMENTO
     * @var string
     */
    public $dt_fs;

    /**
     * FREQUÊNCIA DE REPETIÇÕES DO AGENDAMENTO
     * @var string
     */
    public $freq;

    /**
     * ALERTAS DO AGENDAMENTO
     * @var string
     */
    public $alert;

    /**
     * TIPO DE MANUTENÇÃO PARA ESTE AGENDAMENTO
     * @var string
     */
    public $tipo;

    /**
     * SE VAI REALIZAR INSPEÇÃO
     * @var boolean
     */
    public $inspecao;

    /**
     * DESCRIÇÃO DO AGENDAMENTO
     * @var string
     */
    public $descricao;

    /**
     * STATUS DO AGENDAMENTO
     * @var string
     */
    public $status;

    /**
     * MÉTODO RESPONSÁVEL POR CADASTRAR A INSTANCIA ATUAL NO BANCO DE DADOS
     * @return boolean
     */
    public function cadastrar(){
        //INSERE OS DADOS DO AGENDAMENTO NO BANCO DE DADOS
        $this->id = (new Database('agendamentos'))->insert([
            'id_user'       =>$this->id_user,
            'equipamento'   =>$this->id_equipamento,
            'responsaveis'  =>$this->id_responsaveis,
            'title'         =>$this->title,
            'dt_st'         =>$this->dt_st,
            'dt_fs'         =>$this->dt_fs,
            'freq'          =>$this->freq,
            'alert'         =>$this->alert,
            'tipo'          =>$this->tipo,
            'inspecao'      =>$this->inspecao,
            'descricao'     =>$this->descricao,
            'status'        =>$this->status,
        ]);

        //SUCESSO
        return true;
    }

    /**
     * MÉTODO RESPONSÁVEL POR ATUALIZAR OS DADOS DO BANCO COM A INSTANCIA ATUAL 
     * @return boolean
     */
    public function atualizar(){
        //ATUALIZA OS DADOS DO AGENDAMENTO NO BANCO DE DADOS
        return (new Database('agedamentos'))->update('id = '.$this->id,[
            'equipamento'   =>$this->id_equipamento,
            'responsaveis'  =>$this->id_responsaveis,
            'title'         =>$this->title,
            'dt_st'         =>$this->dt_st,
            'dt_fs'         =>$this->dt_fs,
            'freq'          =>$this->freq,
            'alert'         =>$this->alert,
            'tipo'          =>$this->tipo,
            'inspecao'      =>$this->inspecao,
            'descricao'     =>$this->descricao,
            'status'        =>$this->status,
        ]);
    }

    /**
     * MÉTODO RESPONSÁVEL POR EXCLUIR UM AGENDAMENTO DO BANCO DE DADOS
     * @return boolean
     */
    public function excluir(){
        //EXCLUI UM AGENDAMENTO DO BANCO DE DADOS
        return (new Database('agendamentos'))->delete('id = '.$this->id);
    }


    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR AGENDAMENTOS DO BANCO
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatemet
     */
    public static function getAgendamentos($where = null, $order = null, $limit = null, $fields = '*'){
        return (new Database('agendamentos'))->select($where,$order,$limit,$fields);
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR UM AGENDAMENTO DO BANCO
     * @param integer $id
     * @return Agendamento
     */
    public static function getAgend($id){
        return self::getAgendamentos('id = '.$id)->fetchObject(self::class);
    }
}