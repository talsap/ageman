<?php

namespace App\Http;

class Response{

    /**
     * COÓDIGO DO STATUS HTTP
     * @var integer
     */
    private $httpCode = 200;

    /**
     * CABEÇALHO DO RESPONSE
     * @var array
     */
    private $headers =[];

    /**
     * TIPO DE CONTEÚDO QUE ESTÁ SENDO RETORNADO
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * CONTEÚDO DO RESPONSE
     * @var mixed
     */
    private $content;

    /**
     * MÉTODO RESPONSÁVEL POR INICIAR A CLASSE E DEFINIR VALORES
     * @param integer $httpCode
     * @param mixed   $content
     * @param string  $contentType
     */
    public function __construct($httpCode, $content, $contentType = 'text/html'){
        $this->$httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    /**
     * MÉTODO RESPONSÁVEL EM ALTERAR O CONTENT TYPE DO RESPONSE
     * @param string $contentType
     */
    public function setContentType($contentType){
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    /**
     * MÉTODO RESPONSÁVEL POR ADICIONAR UM REGISTRO NO CABEÇALHO DE RESPONSE
     * @param string $key
     * @param string $value
     */
    public function addHeader($key,$value){
        $this->headers[$key] = $value;
    }

    /**
     * MÉTODO RESPONSÁVEL EM ENVIAR OS HEADERS PARA O NAVEGADOR
     */
    private function sendHeaders(){
        //STATUS
        http_response_code($this->httpCode);

        //ENVIAR HEADERS
        foreach($this->headers as $key=>$value){
            header($key.': '.$value);
        }
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR ENVIAR A RESPOSTA PARA O USUÁRIO
     */
    public function sendResponse(){
        //ENVIA OS HEADERS
        $this->sendHeaders();

        //IMPRIME O CONTEÚDO
        switch ($this->contentType){
            case 'text/html':
                echo $this->content;
                exit;
        }
    }
}