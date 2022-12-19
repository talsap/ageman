<?php

use \App\Http\Response;
use \App\Controller\PageLogin;

//ROTA LOGIN
$obRouter->get('/', [
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request){
        return new Response(200, PageLogin\Login::getLogin($request));
    }
]);

//ROTA LOGIN (POST)
$obRouter->post('/', [
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request){
        return new Response(200, PageLogin\Login::setLogin($request));
    }
]);

//ROTA LOGIN
$obRouter->get('/login', [
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request){
        return new Response(200, PageLogin\Login::getLogin($request));
    }
]);

//ROTA LOGIN (POST)
$obRouter->post('/login', [
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request){
        return new Response(200, PageLogin\Login::setLogin($request));
    }
]);

//ROTA LOGIN GOOGLE - VERIFICA O CODIGO DE ACESSO
$obRouter->get('/login-google', [
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request){
        return new Response(200, PageLogin\Login::setLoginGoogle($request));
    }
]);

//ROTA LOGIN GOOGLE (POST)
$obRouter->post('/login-google', [
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request){
        return new Response(200, PageLogin\Login::setLoginGoogle($request));
    }
]);

//ROTA LOGOUT
$obRouter->get('/logout', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, PageLogin\Login::setLogout($request));
    }
]);


//ROTA RECUPERAR SENHA 
$obRouter->get('/recuperar-senha', [
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request){
        return new Response(200, PageLogin\Forgot::getForgot($request));
    }
]);

//ROTA RECUPERAR SENHA (POST)
$obRouter->post('/recuperar-senha', [
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request){
        return new Response(200, PageLogin\Forgot::setForgot($request));
    }
]);