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
     * NOME DO LOCAL ANTERIORMENTE
     * @var string
     */
    public $localAnt;

    /**
     * NOME DA ÁREA
     * @var string
     */
    public $area;

    /**
     * NOME DA ÁREA ANTERIORMENTE
     * @var string
     */
    public $areaAnt;

    /**
     * MÉTODO RESPONSÁVEL POR CADASTRAR UM LOCAL NO BANCO DE DADOS
     * @return boolean
     */
    public function cadastrarLocal(){
        //INSERE OS DADOS DO LOCAL NO BANCO DE DADOS
        $this->id = (new Database('locais'))->insert([
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
        $this->id = (new Database('locais'))->insert([
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
    public function atualizarLocal(){
        //STRING DE BUSCA SQL
        $sql = 'id_user = '.$this->id_user.' and local = "'.$this->localAnt.'"';
        
        //ATUALIZA TODOS OS LOCAIS DENTRO DA TABELA EQUIPAMENTOS NO BANCO DE DADO
        $atualizaEquipamentos = (new Database('equipamentos'))->update($sql,[
            'local'         =>$this->local
        ]);

        //ATUALIZA OS DADOS DO LOCAL NO BANCO DE DADOS
        return (new Database('locais'))->update($sql,[
            'local'         =>$this->local
        ]);
    }

    /**
     * MÉTODO RESPONSÁVEL POR ATUALIZAR OS DADOS DAS ÁREAS NO BANCO DE DADOS
     * @return boolean
     */
    public function atualizarArea(){
        //STRING DE BUSCA SQL
        $sql = 'id_user = '.$this->id_user.' and local = "'.$this->localAnt.'"'.' and area = "'.$this->areaAnt.'"';

        //ATUALIZA TODOS AS ÁREAS DENTRO DA TABELA EQUIPAMENTOS NO BANCO DE DADO
        $atualizaEquipamentos = (new Database('equipamentos'))->update($sql,[
            'local'        =>$this->local,
            'area'         =>$this->area
        ]);

        //ATUALIZA OS DADOS DA AREA E LOCAL NO BANCO DE DADOS
        return (new Database('locais'))->update('id = '.$this->id,[
            'local'        =>$this->local,
            'area'         =>$this->area
        ]);
    }

    /**
     * MÉTODO RESPONSÁVEL POR EXCLUIR UM LOCAL DO BANCO DE DADOS
     * @return boolean
     */
    public function excluirLocal(){
        //STRING DE BUSCA SQL
        $sql = 'id_user = '.$this->id_user.' and local = "'.$this->local.'"';

        //ATUALIZA TODOS OS LOCAIS E ÁREAS DENTRO DA TABELA EQUIPAMENTOS NO BANCO DE DADO
        $atualizaEquipamentos = (new Database('equipamentos'))->update($sql,[
            'local'         =>'',
            'area'          =>''
        ]);

        //EXCLUI OS LOCAL DO BANCO DE DADOS
        return (new Database('locais'))->delete($sql);
    }

    /**
     * MÉTODO RESPONSÁVEL POR EXCLUIR UM LOCAL E ÁREA DO BANCO DE DADOS
     * @return boolean
     */
    public function excluirArea(){
        //STRING DE BUSCA SQL
        $sql = 'id_user = '.$this->id_user.' and local = "'.$this->local.'"'.' and area = "'.$this->area.'"';

        //ATUALIZA TODOS OS LOCAIS E ÁREAS DENTRO DA TABELA EQUIPAMENTOS NO BANCO DE DADO
        $atualizaEquipamentos = (new Database('equipamentos'))->update($sql,[
            'local'         =>'',
            'area'          =>''
        ]);

        //EXCLUI OS LOCAL DO BANCO DE DADOS
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

    /**
     * MÉTODO RESPONSÁVEL POR OBTER OS DADOS SELECT DAS ÁREA
     * @param iteger $id
     * @return string
     */
    public static function getAreaItens($id, $id_user){
        //OBJETO DO LOCAL
        $obL = self::getLocal($id);

        //PEGA APENAS O LOCAL
        $local = $obL->local;

        //RESULTADOS DO LOCAIS
        $results = self::getLocais('id_user = '.$id_user.' '.'AND local = "'.$local.'"', 'id DESC', NULL);

        //CRIA ARRAY COM AS ÁREAS
        while($obLocal = $results->fetchObject(self::class)){
            if($obLocal->area != ''){
                $itens[] = [
                    'id'         => $obLocal->id,        
                    'area'       => $obLocal->area,
                ];
            }
        }

        //RETORNA AS ÁREAS
        $resultado = ['dados' => $itens];
        return $resultado;
    }
}