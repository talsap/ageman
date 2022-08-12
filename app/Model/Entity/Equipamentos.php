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
    public $dirImage;

    /**
     * HORAS DE USO DIÁRIO DO EQUIPAMENTO
     * @var string
     */
    public $hrsUso;

    /**
     * STATUS DO EQUIPAMENTO
     * @var string
     */
    public $status;

    /**
     * HISTORICO DE MANUTENÇÕES (VALOR/QUANTIDADE DE MANUTENÇÕES FEITAS)
     * @var string
     */
    public $histManu;

    /**
     * MÉTODO RESPONSÁVEL POR CADASTRAR A INSTANCIA ATUAL NO BANCO DE DADOS
     * @return boolean
     */
    public function cadastrar(){
        echo '<pre>';
        print_r($this);
        echo '</pre>'; exit;
        
        //INSERE OS DADOS DO EQUIPAMENTO NO BANCO DE DADOS
        $this->id = (new Database('equipamentos'))->isert([
            'id_user'       =>$this->id_user,
            'patrimonio'    =>$this->patrimonio,
            'nome'          =>$this->patrimonio,
            'descricao'     =>$this->patrimonio,
            'local'         =>$this->patrimonio,
            'dirImage'      =>$this->patrimonio,
            'hrsUso'        =>$this->patrimonio,
            'status'        =>$this->patrimonio,
            'histManu'      =>$this->patrimonio
        ]);

        //SUCESSO
        return true;
    }
}