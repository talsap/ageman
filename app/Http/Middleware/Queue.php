<?php

namespace App\Http\Middleware;

class Queue{


    /**
     * MAPEAMENTO DE MIDDLEWARES
     * @var array 
     */
    private static $map = [];

    /**
     * MAPEAMENTO DE MIDDLEWARE QUE SERÃO CARREGADOS EM TODAS AS ROTAS
     * @var array
     */
    private static $default = [];

    /**
     * FILA DE MIDDLEWARES A SEREM EXECUTADOS
     * @var array
     */
    private $middlewares = [];

    /**
     * FUNÇÃO DE EXUCUÇÃO DO CONTROLADOR
     * @var Closure
     */
    private $controller;

    /**
     * ARGUMENTOS DA FUNÇÃO DO CONTROLADOR
     * @var array
     */
    private $controllerArgs = [];

    /**
     * MÉTODO RESPONSÁVEL POR CONSTRUIR A CLASSE DE FILA DE MIDDLEWARES
     * @param array $middlewares
     * @param Closure $controller
     * @param array $controllerArgs
     */
    public function __construct($middlewares, $controller, $controllerArgs){
        $this->middlewares    = array_merge(self::$default, $middlewares);
        $this->controller     = $controller;
        $this->controllerArgs = $controllerArgs;
    }

    /**
     * MÉTODO RESPONSÁVEL POR DEFINIR O MAPEAMENTO DE MIDDLEWARES
     * @param array $map
     */
    public static function setMap($map){
        self::$map = $map;
    }

    /**
     * MÉTODO RESPONSÁVEL POR DEFINIR O MAPEAMENTO DE MIDDLEWARES PADRÕES
     * @param array $default
     */
    public static function setDefault($default){
        self::$default = $default;
    }

    /**
     * MÉTODO RESPONSÁVEL POR EXECUTAR O PRÓXIMO NÍVEL DA FILA DE MIDDLEWARES
     * @param Request $request
     * @return Response
     */
    public function next($request){
        //VERIFICA SE A FILA ESTÁ VAZIA 
        if(empty($this->middlewares)) return call_user_func_array($this->controller,$this->controllerArgs);

        //MIDDLEWARE
        $middleware = array_shift($this->middlewares);

        //VERIFICA O MAPEAMENTO
        if(!isset(self::$map[$middleware])){
            throw new \Exception("Problemas ao processar o middleware da requisição", 500);
        }

        //NEXT
        $queue = $this;
        $next = function($request) use($queue){
            return $queue->next($request);
        };

        //EXECUTA O MIDDLEWARE
        return (new self::$map[$middleware])->handle($request, $next);
    }

}