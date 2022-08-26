<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class Locais{

    /**
     * ID DO LOCAL
     * @var integer
     */
    public $id;

    /**
     * ID DO USUÁRIO LOGADO
     * @var integer
     */
    public $id_user;
    
    /**
     * NOME DO LOCAL
     * @var string
     */
    public $local;

    /**
     * NOME DA ÁREA
     * @var string
     */
    public $area;

    /**
     * MÉTODO RESPONSÁVEL POR CADASTRAR UM LOCAL NO BANCO DE DADOS
     * @return boolean
     */
    public function cadastrarLocal(){
        //INSERE OS DADOS DO LOCAL NO BANCO DE DADOS
        $this->idLocal = (new Database('locais'))->insert([
            'id_user'       =>$this->id_user,
            'local'         =>$this->local,
        ]);

        //SUCESSO
        return true;
    }

    /**
     * MÉTODO RESPONSÁVEL POR CADASTRAR UMA ÁREA NO BANCO DE DADOS
     * @return boolean
     */
    public function cadastrarArea(){
        //INSERE OS DADOS DA AREA NO BANCO DE DADOS
        $this->idArea = (new Database('area'))->insert([
            'id_user'       =>$this->id_user,
            'local'         =>$this->local,
            'area'          =>$this->area
        ]);

        //SUCESSO
        return true;
    }

    /**
     * MÉTODO RESPONSÁVEL POR ATUALIZAR O LOCAL NO BANCO DE DADOS
     * @return boolean
     */
    public function atualizarLocal($localAnt){
        //ATUALIZA TODOS OS LOCAIS DENTRO DA TABELA EQUIPAMENTOS NO BANCO DE DADO
        $atualizaEquipamentos = (new Database('equipamentos'))->update('id_user = '.$this->id_user,[
            'local'         =>$this->local
        ]);

        //ATUALIZA OS DADOS DO LOCAL NO BANCO DE DADOS
        return (new Database('locais'))->update('id_user = '.$this->id_user,[
            'local'         =>$this->local
        ]);
    }

    /**
     * MÉTODO RESPONSÁVEL POR ATUALIZAR OS DADOS DAS ÁREAS NO BANCO DE DADOS
     * @return boolean
     */
    public function atualizarArea(){
        //ATUALIZA TODOS AS ÁREAS DENTRO DA TABELA EQUIPAMENTOS NO BANCO DE DADO
        $atualizaEquipamentos = (new Database('equipamentos'))->update('id_user = '.$this->id_user,[
            'area'         =>$this->area
        ]);

        //ATUALIZA OS DADOS DA AREA NO BANCO DE DADOS
        return (new Database('locais'))->update('id = '.$this->id,[
            'area'    =>$this->area,
        ]);
    }

    /**
     * MÉTODO RESPONSÁVEL POR EXCLUIR UM LOCAL DO BANCO DE DADOS
     * @return boolean
     */
    public function excluirLocal(){
        $local = '';
        $area  = '';

        //ATUALIZA TODOS OS LOCAIS E ÁREAS DENTRO DA TABELA EQUIPAMENTOS NO BANCO DE DADO
        $atualizaEquipamentos = (new Database('equipamentos'))->update('id_user = '.$this->id_user,[
            'local'         =>$this->local,
            'area'          =>$this->area
        ]);

        //EXCLUI O LOCAL DO BANCO DE DADOS
        return (new Database('locais'))->delete('id = '.$this->id);
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR LOCAIS DO BANCO
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatemet
     */
    public static function getLocais($where = null, $order = null, $limit = null, $fields = '*'){
        return (new Database('locais'))->select($where,$order,$limit,$fields);
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR OS LOCAIS E ÁREAS DO BANCO DE DADOS
     * @param integer $id
     * @return Locais
     */
    public static function getLocal($id){
        return self::getLocais('id = '.$id)->fetchObject(self::class);
    }
}