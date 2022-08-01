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
     * MÉTODO RESPONSÁVEL POR RETORNAR UM USUÁRIO COM BASE EM SEU E-MAIL
     * @param string $email
     * @return User
    */
    public static function getUserByEmail($email){
        return (new Database('usuarios'))->select('email = "'.$email.'"')->fetchObject(self::class);
    }

}