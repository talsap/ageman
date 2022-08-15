<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class Equipamentos{

    /**
     * ID DO EQUIPAMENTO
     * @var integer
     */
    public $id;

    /**
     * ID DO USUÁRIO LOGADO
     * @var integer
     */
    public $id_user;
    
    /**
     * PATRIMÔNIO DO EQUIPAMENTOS
     * @var string
     */
    public $patrimonio;

    /**
     * NOME DO EQUIPAMENTO
     * @var string
     */
    public $nome;

    /**
     * DESCRIÇÃO DO EQUIPAMENTOS
     * @var string
     */
    public $descricao;

    /**
     * LOCAL DO EQUIPAMENTO
     * @var string
     */
    public $local;

    /**
     * DIR DE UMA IMAGEM PARA O EQUIPAMENTO
     * @var string
     */
    public $imagem;

    /**
     * HORAS DE USO DIÁRIO DO EQUIPAMENTO
     * @var string
     */
    public $horas;

    /**
     * STATUS DO EQUIPAMENTO
     * @var string
     */
    public $status;

    /**
     * HISTORICO DE MANUTENÇÕES (VALOR/QUANTIDADE DE MANUTENÇÕES FEITAS)
     * @var integer
     */
    public $hist_manu;

    /**
     * MÉTODO RESPONSÁVEL POR CADASTRAR A INSTANCIA ATUAL NO BANCO DE DADOS
     * @return boolean
     */
    public function cadastrar(){
        //INSERE OS DADOS DO EQUIPAMENTO NO BANCO DE DADOS
        $this->id = (new Database('equipamentos'))->insert([
            'id_user'       =>$this->id_user,
            'patrimonio'    =>$this->patrimonio,
            'nome'          =>$this->nome,
            'descricao'     =>$this->descricao,
            'local'         =>$this->local,
            'imagem'        =>$this->imagem,
            'horas'         =>$this->horas,
            'status'        =>$this->status,
            'hist_manu'     =>$this->hist_manu
        ]);

        //SUCESSO
        return true;
    }

    /**
     * MÉTODO RESPONSÁVEL POR ATUALIZAR OS DADOS DO BANCO COM A INSTANCIA ATUAL 
     * @return boolean
     */
    public function atualizar(){
        //ATUALIZA OS DADOS DO EQUIPAMENTO NO BANCO DE DADOS
        return (new Database('equipamentos'))->update('id = '.$this->id,[
            'patrimonio'    =>$this->patrimonio,
            'nome'          =>$this->nome,
            'descricao'     =>$this->descricao,
            'local'         =>$this->local,
            'imagem'        =>$this->imagem,
        ]);
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR EQUIPAMENTOS DO BANCO
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatemet
     */
    public static function getEquipamentos($where = null, $order = null, $limit = null, $fields = '*'){
        return (new Database('equipamentos'))->select($where,$order,$limit,$fields);
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR UM EQUIPAMENTO DO BANCO
     * @param integer $id
     * @return Equipamento
     */
    public static function getEquip($id){
        return self::getEquipamentos('id = '.$id)->fetchObject(self::class);
    }
}