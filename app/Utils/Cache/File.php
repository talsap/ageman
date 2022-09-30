<?php

namespace App\Utils\Cache;

class File{

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR O CAMINHO ATÉ O ARQUIVO DE CACHE
     * @param string $hash
     * @return string
     */
    private static function getFilePath($hash){
        //CRIA UM LOCAL PARA O USUÁRIO
        $locale = 'User'.$_SESSION['admin']['usuario']['id'];

        //DIRETÓRIO DE CACHE
        $dir = getenv('CACHE_DIR').'/'.$locale;

        //VERIFICA A EXISTÊNCIA DO DIRETÓRIO
        if(!file_exists($dir)){
            mkdir($dir,0755,true);
        }

        //RETORNA O CAMINHO ATÉ O ARQUIVO
        return $dir.'/'.$hash;
    }

    /**
     * MÉTODO RESPONSÁVEL POR GUARDAR INFORMAÇÕES NO CACHE
     * @param string $hash
     * @param mixed $content
     * @return boolean
     */
    private static function storegeCache($hash, $content){
        //SERIALIZA O RETORNO
        $serialize = serialize($content);

        //OBTÉM O CAMINHO ATÉ O ARQUIVO DE CACHE
        $cacheFile = self::getFilePath($hash);
        
        //GRAVA AS INFORMAÇÕES NO ARQUIVO
        return file_put_contents($cacheFile, $serialize);
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR O CONTEÚDO GRAVADO NO CACHE
     * @param string $hash
     * @param integer $expiration
     * @return mixed
     */
    private static function getContentCache($hash, $expiration){
        //OBTÉM O CAMINHO DO ARQUIVO
        $cacheFile = self::getFilePath($hash);
        
        //VERIFICA A EXISTÊNCIA DO ARQUIVO
        if(!file_exists($cacheFile)){
            return false;
        }

        //VALIDA A EXPIRAÇÃO DO CACHE
        $createTime = filectime($cacheFile);
        $diffTime = Time() - $createTime;
        if($diffTime > $expiration){
            return false;
        }

        //RETORNA O DADO REAL
        $serialize = file_get_contents($cacheFile);
        return unserialize($serialize);
    }

    /**
     * MÉTODO RESPONSÁVEL POR OBTER UMA INFORMAÇÃO DO CACHE
     * @param string $hash
     * @param integer $expiration
     * @param Closure $function
     * @return mixed
     */
    public static function getCache($hash, $expiration, $function){
        //VEROIFICA O CONTEÚDO GRAVADO
        if($content = self::getContentCache($hash, $expiration)){
            return $content;
        }
        
        //EXECUÇÃO DA FUNÇÃO
        $content = $function();
        
        //GRAVA O RETORNO NO CACHE
        self::storegecache($hash, $content);

        //RETORNA O CONTEÚDO
        return $content;
    }














}