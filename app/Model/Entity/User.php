<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class User{

    /**
     * ID DO USUÁRIO
     * @var integer
     */
    public $id;
    
    /**
     * NOME DO USUARIO
     * @var string
     */
    public $nome;

    /**
     * E-MAIL DO USUÁRIO
     * @var string
     */
    public $email;

    /**
     * SENHA DO USUÁRIO
     * @var string
     */
    public $senha;

    /**
     * REFRESH TOKEN DO USUÁRIO GOOGLE
     * @var string
     */
    public $refresh_token;

    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR UM USUÁRIO COM BASE EM SEU E-MAIL
     * @param string $email
     * @return User
    */
    public static function getUserByEmail($email){
        return (new Database('usuarios'))->select('email = "'.$email.'"')->fetchObject(self::class);
    }

    /**
     * MÉTODO RESPONSÁVEL POR ATUALIZAR OS DADOS DO BANCO COM A INSTANCIA ATUAL 
     * @return boolean
     */
    public function atualizar(){
        //ATUALIZA OS DADOS DO RESPONSÁVEL NO BANCO DE DADOS
        return (new Database('usuarios'))->update('id = '.$this->id,[
            'nome'          =>$this->nome,
            'email'         =>$this->email,
            'refresh_token' =>$this->refresh_token
        ]);
    }
}