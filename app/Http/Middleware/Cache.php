<?php

namespace App\Http\Middleware;

use \App\Utils\Cache\File as CacheFile;

class Cache{
    /**
     * MÉTODO RESPONSÁVEL POR VERIFICAR SE A REQUEST ATUAL PODE SER CACHEADA
     * @param Request $request
     * @return boolean
     */
    private function isCacheable($request){
        //VALIDA O TEMPO DE CACHE
        if(getenv('CACHE_TIME')<= 0){
            return false;
        }

        //VALIDA O MÉTODO DA REQUISIÇÃO
        if($request->getHttpMethod() != 'GET'){
            return false;
        }

        //CACHEÁVEL
        return true;
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR A HASH DO CACHE
     * @param Request $request
     * @return string
     */
    private function getHash($request){
        //URI DA ROTA
        $uri = $request->getRouter()->getUri();

        //QUERY PARAMS
        $queryParams = $request->getQueryParams();
        $uri .= !empty($queryParams) ? '?'.http_build_query($queryParams) : '';

        //REMOVE AS BARRAS E RETORNA A HASH
        return preg_replace('/[^0-9a-zA-z]/', '-', ltrim($uri, '/'));
    }

    /**
     * MÉTODO RESPONSÁVEL POR EXECUTAR O MIDDLEWARE
     * @param Request $request
     * @param Clousure next
     * @return Response
     */
    public function handle($request, $next){
        //VERIFICA SE A REQUEST ATUTAL É CACHEÁVEL
        if(!$this->isCacheable($request)) return $next($request); 

        //HASH DO CACHE
        $hash = $this->getHash($request);

        //RETORNA OS DADOS DO CACHE
        return CacheFile::getCache($hash,getenv('CACHE_TIME'), function() use($request, $next){
            return $next($request);
        });
    }


}