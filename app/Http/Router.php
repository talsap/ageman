<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;
use \App\Http\Middleware\Queue as MiddlewareQueue;

class Router{

    /**
     * URL COMPLETA DO PROJETO (RAIZ)
     * @var string
     */
    private $url = '';

    /**
     * PREFIXO DE TODAS AS ROTAS
     * @var string
     */
    private $prefix = '';

    /**
     * ÍNDICE DE ROTAS
     * @var array
     */
    private $routes = [];

    /**
     * INSTANCIA DE REQUEST
     * @var Request
     */
    private $request;

    /**
     * MÉTODO RESPONSÁVEL POR INICIAR A CLASSE
     * @param string $url
     */
    public function __construct($url){
        $this->request = new Request($this);
        $this->url = $url;
        $this->setPrefix();
    }

    /**
     * MÉTODO RESPONSÁVEL POR DEFINIR O PREFIXO DAS ROTAS
     */
    private function setPrefix(){
        //INFO DA URL ATUAL
        $parseUrl = parse_url($this->url);
        
        //DEFINE O PREFIXO
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * MÉTODO POR ADICIONAR UMA ROTA NA CLASSE
     * @param string $method
     * @param string $route
     * @param array  $params
     */
    public function addRoute($method, $route, $params = []){
        //VALIDAÇÃO DOS PARÂMETROS
        foreach($params as $key=>$value){
            if($value instanceof Closure){
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }
    
        //MIDDLEWARES DA ROTA
        $params['middlewares'] = $params['middlewares'] ?? [];

        //VARIÁVEIS DA ROTA
        $params['variables'] = [];

        //PADRÃO DE VALIDAÇÃO DAS VARIÁVEIS DAS ROTAS
        $patternVariable = '/{(.*?)}/';
        if(preg_match_all($patternVariable, $route, $matches)){
            $route = preg_replace($patternVariable,'(.*?)',$route);
            $params['variables'] = $matches[1];
        }

        //PADRÃO DE VALIDAÇÃO DA URL
        $patternRoute = '/^'.str_replace('/','\/',$route).'$/';

        //ADICIONA A ROTA DENTRO DA CLASSE
        $this->routes[$patternRoute][$method] = $params;
    }

    /**
     * MÉTODO RESPONSÁVEL POR DEFINIR UMA ROTA DE GET
     * @param string $route
     * @param array  $params
     */
    public function get($route, $params = []){
        return $this->addRoute('GET', $route, $params);
    }

    /**
     * MÉTODO RESPONSÁVEL POR DEFINIR UMA ROTA DE POST
     * @param string $route
     * @param array  $params
     */
    public function post($route, $params = []){
        return $this->addRoute('POST', $route, $params);
    }

    /**
     * MÉTODO RESPONSÁVEL POR DEFINIR UMA ROTA DE PUT
     * @param string $route
     * @param array  $params
     */
    public function put($route, $params = []){
        return $this->addRoute('PUT', $route, $params);
    }

    /**
     * MÉTODO RESPONSÁVEL POR DEFINIR UMA ROTA DE DELETE
     * @param string $route
     * @param array  $params
     */
    public function delete($route, $params = []){
        return $this->addRoute('DELETE', $route, $params);
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR A URI DESCONSIDERANDO O PREFIXO
     * @return string
     */
    public function getUri(){
        //URI DA REQUEST
        $uri = $this->request->getUri();

        //FATIA A URI COM O PREFIXO
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        //RETORNA A URI SEM O PREFIXO
        return end($xUri);
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR OS DADOS DA ROTA ATUAL
     * @return array
     */
    private function getRoute(){
        //URI
        $uri = $this->getUri();
        
        //METHOD
        $httpMethod = $this->request->getHttpMethod();

        //VALIDA AS ROTAS
        foreach($this->routes as $patternRoute=>$methods){
            //VERIFICA SE A URI BATE COM O PADRÃO
            if(preg_match($patternRoute,$uri,$matches)){
                //VERIFICA O MÉTODO
                if(isset($methods[$httpMethod])){
                    //REMOVE A PRIMEIRA POSIÇÃO
                    unset($matches[0]);

                    //VARIÁVEIS PROCESSADAS
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    //RETORNO DOS PARÂMETROS DA ROTA
                    return $methods[$httpMethod];
                }

                //MÉTODO NÃO PERMITIDO/DEFINIDO
                throw new Exception("Método não permitido", 405);
            }
        }

        //URL NÃO ENCONTRADA
        throw new Exception("URL não encontrada", 404);
    }

    /**
     * MÉTODO RESPONSÁVEL POR EXECUTAR A ROTA ATUAL
     * @return Response
     */
    public function run(){
        try{
            //OBTÉM A ROTA ATUAL
            $route = $this->getRoute();
            
            //VERIFICA O CONTROLADOR
            if(!isset($route['controller'])){
                throw new Exception("A URL não pode ser processada", 500);
            }

            //ARGUMENTOS DA FUNÇÃO
            $args = [];

            //REFLECTIONFUNCTION
            $reflection = new ReflectionFunction($route['controller']);
            foreach($reflection->getParameters() as $parameter){
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            //RETORNA A EXECUÇÃO DA FILA DE MIDDLEWARES
            return (new MiddlewareQueue($route['middlewares'], $route['controller'], $args))->next($this->request);
        }catch(Exception $e){
            return new Response($e->getCode(),$e->getMessage());
        }
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR A URL ATUAL
     * @return string
     */
    public function getCurrentUrl(){
        return $this->url.$this->getUri();
    }

    /**
     * MÉTODO RESPONSÁVEL POR DIRECIONAR A URL
     * @param string $route
     */
    public function redirect($route){
        //URL
        $url = $this->url.$route;
        
        //EXECUTA O REDIRECT
        header('location: '.$url);
        exit;
    }
}   