<?php

namespace App\File;

class Uploud{

    /**
     * Nome do arquivo (sem extensão)
     * @var string 
     */
    private $name;

    /**
     * Extensão do arquivo (sem ponto)
     * @var string
     */
    private $extension;

    /**
     * Type do arquivo
     * @var string
     */
    private $type;

    /**
     * Nome temporário/caminho temporário do arquivo
     * @var string
     */
    private $tmpName;

    /**
     * Código de erro do uploud
     * @var integer
     */
    private $error;

    /**
     * Tamanho do arquivo
     * @var integer
     */
    private $size;

    /**
     * Contador de duplicação de arquivo
     * @var integer
     */
    private $duplicates = 0;

    /**
     * Construtor da Classe
     * @param array $file $_FILES[campo]
     */
    public function __construct($file){
        $this->type     = $file['type'];
        $this->tmpName  = $file['tmp_name'];
        $this->error    = $file['error'];
        $this->size     = $file['size'];
        if($this->error!=4){
            $info = pathinfo($file['name']);
            $this->name = $info['filename'];
            $this->extension = $info['extension'];
        }        
    }

    /**
     * Retorna o type do arquivo
     * @return string
     */
    public function getType(){
        return $this->type;
    }

    /**
     * Retorna o caminho temporário do arquivo
     * @return string
     */
    public function getTmpName(){
        return $this->tmpName;
    }

    /**
     * Retorna a extension do arquivo
     * @return string
     */
    public function getExtension(){
        return $this->extension;
    }

    /**
     * Retorna o tamanho do arquivo
     * @return integer
     */
    public function getSize(){
        return $this->size;
    }

    /**
     * Retorna o nome do arquivo
     * @return string
     */
    public function getName(){
        return $this->name;
    }

    /**
     * Retorna o erro ocorrido
     * @return integer
     */
    public function getError(){
        return $this->error;
    }

    /**
     * Método responsável por alterar o nome do arquivo
     * @param string $name
     */
    public function setName($name){
        $this->name = $name;
    }

    /**
     * Método responsável por retornar o nome do arquivo com sua extensão
     * @return string
     */
    public function getBasename(){
        //VALIDA A EXTENSÃO
        $extension = strlen($this->extension) ? '.'.$this->extension : '';

        //VALIDA DUPLICAÇÃO
        $duplicates = $this->duplicates > 0 ? '-'.$this->duplicates : '';

        //RETORNA O NOME COMPLETO
        return $this->name.$duplicates.$extension;
    }

    /**
     * Método responsável por obter um nome possível para o arquivo
     * @param string $dir
     * @param boolean $overwrite
     * @return string
     */ 
    private function getPossibleBasename($dir, $overwrite){
        //SOBRESCREVE ARQUIVO
        if($overwrite) return $this->getBasename();

        //NÃO PODE SOBRESCREVER O ARQUIVO
        $basename =  $this->getBasename();

        //VERIFICA DUPLICAÇÃO
        if(!file_exists($dir.'/'.$basename)){
            return $basename;
        }

        //INCREMENTAR DUPLICAÇÕES
        $this->duplicates++;

        //RETORNO DO PRÓPRIO MÉTODO
        return $this->getPossibleBasename($dir, $overwrite);
    }

    /**
     * Método responsável por mover o arquivo de uploud
     * @param string $dir
     * @param boolean $overwrite
     * @return boolean
     */
    public function uploud($dir, $overwrite){
        //VERIFICA SE HÁ ERRO 
        if($this->error != 0) return false;

        //CAMINHO COMPLETO DE DESTINO
        $path = $dir.'/'.$this->getPossibleBasename($dir, $overwrite);
            
        //MOVE O ARQUIVO PARA A PASTA DE DESTINO
        move_uploaded_file($this->tmpName,$path);

        //RETORNA O DIRETÓRIO PARA ONDE O ARQUIVO FOI MOVIDO
        return $path;
    }

    /**
     * Verifica se a extensão é permitida
     * @param string $extension
     * @param array $extensionPermitidas
     * @return boolean
     */
    public function verificaExtension($extension, $extensionPermitidas){
        //VERIFICA SE A EXTENSÃO É PERMITIDA
        if(in_array($extension, $extensionPermitidas)){
            return true;
        }else{
            //RETORNA FALSO
            return false;
        }
    }
}