<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class Responsaveis{

    /**
     * ID DO RESPONSÁVIL
     * @var integer
     */
    public $id;

    /**
     * ID DO USUÁRIO LOGADO
     * @var integer
     */
    public $id_user;

    /**
     * NOME DO RESPONSÁVEL
     * @var string
     */
    public $nome;

    /**
     * EMAIL DO RESPONSÁVEL
     * @var string
     */
    public $email;

    /**
     * MÉTODO RESPONSÁVEL POR CADASTRAR A INSTANCIA ATUAL NO BANCO DE DADOS
     * @return boolean
     */
    public function cadastrar(){
        //INSERE OS DADOS DO RESPONSAVEL NO BANCO DE DADOS
        $this->id = (new Database('responsaveis'))->insert([
            'id_user'       =>$this->id_user,
            'nome'          =>$this->nome,
            'email'         =>$this->email,
        ]);

        //SUCESSO
        return true;
    }

    /**
     * MÉTODO RESPONSÁVEL POR ATUALIZAR OS DADOS DO BANCO COM A INSTANCIA ATUAL 
     * @return boolean
     */
    public function atualizar(){
        //ATUALIZA OS DADOS DO RESPONSÁVEL NO BANCO DE DADOS
        return (new Database('responsaveis'))->update('id = '.$this->id,[
            'nome'          =>$this->nome,
            'email'         =>$this->email,
        ]);
    }

    /**
     * MÉTODO RESPONSÁVEL POR ATUALIZAR OS DADOS DO BANCO COM A INSTANCIA ATUAL ALTERNADO O AGENDAMENTO
     * @return boolean
     */
    public function atualizarAmpla(){
        //STRING DE BUSCA SQL
        $sql = 'id_user = '.$this->id_user.' and responsaveis LIKE "%'.$this->id.'%"';

        //ATUALIZA TODOS OS AGENDAMENTO NO BANCO DE DADOS MUDANDO O STATUS
        $atualizaAgendamentos = (new Database('agendamentos'))->update($sql,[
            'status'          =>'success'
        ]);

        //ATUALIZA OS DADOS DO RESPONSÁVEL NO BANCO DE DADOS
        return (new Database('responsaveis'))->update('id = '.$this->id,[
            'nome'          =>$this->nome,
            'email'         =>$this->email,
        ]);
    }

    /**
     * MÉTODO RESPONSÁVEL POR EXCLUIR UM RESPONSÁVEL DO BANCO DE DADOS
     * @return boolean
     */
    public function excluir(){
        //STRING DE BUSCA SQL
        $sql = 'id_user = '.$this->id_user.' and responsaveis LIKE "%'.$this->id.'%"';

        //ATUALIZA TODOS OS AGENDAMENTO NO BANCO DE DADOS MUDANDO O STATUS
        $atualizaAgendamentos = (new Database('agendamentos'))->update($sql,[
            'status'          =>'warning'
        ]);

        //EXCLUI UM EQUIPAMENTO DO BANCO DE DADOS
        return (new Database('responsaveis'))->delete('id = '.$this->id);
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR RESPONSÁVEIS DO BANCO
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatemet
     */
    public static function getResponsaveis($where = null, $order = null, $limit = null, $fields = '*'){
        return (new Database('responsaveis'))->select($where,$order,$limit,$fields);
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR UM RESPONSÁVEL DO BANCO
     * @param integer $id
     * @return Responsavel
     */
    public static function getResp($id){
        return self::getResponsaveis('id = '.$id)->fetchObject(self::class);
    }
}