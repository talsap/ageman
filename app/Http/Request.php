<?php

namespace App\Http;

class Request{
    /**
     * INSTÂNCIA DO ROUTER
     * @var $router
     */
    private $router;

    /**
     * MÉTODO HTTP DA REQUISIÇÃO
     * @var string
     */
    private $httpMethod;

    /**
     * URI DA PÁGINA
     * @var string
     */
    private $uri;

    /**
     * PARÂMETROS DA URL ($_GET)
     * @var array
     */
    private $queryParams = [];

    /**
     * VARIÁVEIS RECEBIDAS NO POST DA PÁGINA ($_POST)
     * @var
     */
    private $postVars = [];

    /**
     * CABEÇALHO DA REQUISIÇÃO
     * @var array
     */
    private $headers = [];

    public function __construct($router){
        $this->router       = $router;
        $this->queryParams  = $_GET ?? [];
        $this->postVars     = $_POST ?? [];
        $this->headers      = getallheaders();
        $this->httpMethod   = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->setUri();
    }

    /**
     * MÉTODO RESPONSÁVEL POR DEFINIR A URI
     */
    private function setUri(){
        //URI COMPLETA (COM GETS)
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';

        //REMOVE GETS DA URI
        $xURI = explode('?', $this->uri);
        $this->uri = $xURI[0];
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR A INSTÂNCIA DE ROUTER
     * @return Router
     */
    public function getRouter(){
        return $this->router;
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR O MÉTODO HTTP DA REQUISIÇÃO
     * @return string
     */
    public function getHttpMethod(){
        return $this->httpMethod;
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR A URI DA REQUISIÇÃO
     * @return string
     */
    public function getUri(){
        return $this->uri;
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR OS HEADERS DA REQUISIÇÃO
     * @return array
     */
    public function getHeaders(){
        return $this->headers;
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR OS PARAMETROS DA URL DA REQUISIÇÃO
     * @return array
     */
    public function getQueryParams(){
        return $this->queryParams;
    }

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR AS VARIÁVEIS POST DA REQUISIÇÃO
     * @return array
     */
    public function getPostVars(){
        return $this->postVars;
    }
}