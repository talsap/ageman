<?php

namespace App\Controller\PageLogin;

use \App\Utils\View;
use \App\Model\Entity\User;
use \App\Session\Admin\Login as SessionAdminLogin;
use Google;

class Login extends Page{
    /**
     * MÉTODO RESPONSAVEL POR RETORNAR A RENDERIZAÇÃO (VIEW) DA NOSSA PAGINA DE LOGIN
     * @param Request $request
     * @param string  $erroMessage
     * @return string 
    */
    public static function getLogin($request, $errorMessage = null){
        //STATUS
        $status = !is_null($errorMessage) ? Alert::getErro($errorMessage) : '';
        
        //CONTEÚDO DA PÁGINA DE LOGIN
        $content = View::render('PageLogin/login', [
            'title' => 'MANUUFRB - Login',
             'name' => 'MANUUFRB',
            'status' => $status
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPage('MANUUFRB - Login', $content);
    }

    /**
     * MÉTODO RESPONSÁVEL POR DEFINIR O LOGIN DO USUÁRIO
     * @param Request $request
     */
    public static function setLogin($request){
        //POST VARS
        $postVars = $request->getPostVars();
        
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? ''; 

        //BUSCA O USUÁRIO PELO E-MAIL
        $obUser = User::getUserByEmail($email);
        if(!$obUser instanceof User){
            return self::getLogin($request, 'E-mail ou senha inválidos');
        }

        //VERIFICA A SENHA DO USUÁRIO
        if(!password_verify($senha, $obUser->senha)){
            return self::getLogin($request, 'E-mail ou senha inválidos');
        }

        //CRIA A SESSÃO DE LOGIN
        SessionAdminLogin::login($obUser, '', '', '');
        
        //REDIRECIONA O USUÁRIO PARA O /ADMIN
        $request->getRouter()->redirect('/admin');
    }

    /**
     * MÉTODO RESPONSÁVEL POR DEFINIR O LOGIN DO USUÁRIO PELO GOOGLE
     * @param Request $request
     */
    public static function setLoginGoogle($request){
        //ATIVA O BUFFER INTERNO
        ob_start();

        //DETERMINA OS ESCOPOS DA APLICAÇÃO PARA USO DAS APIS
        $scopes = [Google\Service\Calendar::CALENDAR, 
                   Google\Service\Calendar::CALENDAR_EVENTS
        ];

        //CRIA UMA NOVA INSTÂNCIA DE CLIENTE GOOGLE
        $client = new Google\Client();
        $client->setClientId(ID_OAUTH);
        $client->setClientSecret(CLIENT_SECRET);
        $client->addScope($scopes);
        $client->setRedirectUri(URL.'/login-google');
        $client->setAccessType('offline');
        $client->setIncludeGrantedScopes(true);

        
        //VERIFICA SE O MÉTODO É O POST (LOGIN)
        if($request->getHttpMethod() == 'POST'){
            //POST VARS
            $postVars = $request->getPostVars();

            //VERIFICA OS CAMPOS OBRIGATÓRIOS
            if(!isset($postVars['credential']) || !isset($postVars['g_csrf_token'])){
                //REDIRECIONA O USUÁRIO PARA O /
                $request->getRouter()->redirect('/');
                exit;
            }
            //COOKIE CSRF
            $cookie = $_COOKIE['g_csrf_token'] ?? '';

            //VERIFICA O VALOR DO COOKIE E O DO POST PARA O CSRF
            if($postVars['g_csrf_token'] != $cookie){
                //REDIRECIONA O USUÁRIO PARA O /
                $request->getRouter()->redirect('/');
                exit;
            }

            //OBTÉM OS DADOS DO USUÁRIO COM BASE NO JWT
            $payload = $client->verifyIdToken($postVars['credential']); //CREDENTIAL=JWT

            //VERIFICA OS DADOS DO PAYLOAD
            if(isset($payload['email'])){
                $email = $payload['email'] ?? '';
            }else{
                //REDIRECIONA O USUÁRIO PARA O /
                $request->getRouter()->redirect('/');
                exit;
            }
            
            //BUSCA O USUÁRIO PELO E-MAIL E VERIFICA SE EXISTE
            $obUser = User::getUserByEmail($email);
            if(!$obUser instanceof User){
                return self::getLogin($request, 'Usuário inválido!');
            }

            //FINALIZA SETANDO MAIS DADOS PARA A INSTÂNCIA DO CLIENTE GOOGLE
            $client->setLoginHint($email);
            $client->setAccessToken($postVars['credential']);

            //CRIA A URi GOOGLE DE REDIRECIONAMENTO PRA A AUTORIZAÇÃO DO USO DAS APIS
            $auth_url = $client->createAuthUrl();
            
            //DIRECIONA O USUÁRIO PARA A PAGINA DE AUTORIZAÇÃO
            header('location: '.filter_var($auth_url, FILTER_SANITIZE_URL));
            die;
        }
        //VERIFICA SE O MÉTODO É O GET (AUTORIZAÇÃO DAS APIS)
        if($request->getHttpMethod() == 'GET'){
            $error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);
            $code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);
            
            if($error){
                return self::getLogin($request, 'É preciso autorizar para continuar!');
            }
            if($code){
                //OBTÉM O TOKEN DE ACCESS
                $token = $client->fetchAccessTokenWithAuthCode($code);

                //VERIFICA SE NÃO HÁ ERROS
                if(isset($token['id_token'])){
                    //OBTÉM OS DADOS DO USUÁRIO COM BASE NO JWT
                    $payload = $client->verifyIdToken($token['id_token']); //CREDENTIAL=JWT

                    //VERIFICA OS DADOS DO PAYLOAD
                    if(isset($payload['email'])){
                        $email = $payload['email'] ?? '';
                    }else{
                        //REDIRECIONA O USUÁRIO PARA O /
                        $request->getRouter()->redirect('/');
                        exit;
                    }

                    //BUSCA O USUÁRIO PELO E-MAIL
                    $obUser = User::getUserByEmail($email);

                    //CRIA A SESSÃO DE LOGIN
                    SessionAdminLogin::login($obUser, $token['id_token'], $token['access_token'], $token['refresh_token']);
                    
                    //REDIRECIONA O USUÁRIO PARA O /ADMIN
                    $request->getRouter()->redirect('/admin');
                }else{
                    return self::getLogin($request, 'Erro na autorização!');
                }
            }
        }

        //FINALIZA O BUFFER INTERNO
        ob_end_flush();
    }

    /**
     * MÉTODO RESPONSÁVEL POR DESLOGAR O USUÁRIO
     * @param Request $request
     */
    public static function setLogout($request){
        //DESTROI A SESSÃO DE LOGIN
        SessionAdminLogin::logout();

        //REDIRECIONA O USUÁRIO PARA A TELA DE LOGIN
        $request->getRouter()->redirect('/');
    }
}

