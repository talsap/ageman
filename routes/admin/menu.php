<?php

use \App\Http\Response;
use \App\Controller\Pages;

//ROTA ORDEM
$obRouter->get('/ordens-servicos', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Pages\Ordem::getOrdem($request));
    }
]);

//ROTA RESPONSÁVEIS
$obRouter->get('/responsaveis', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Pages\Responsaveis::getResponsaveis($request));
    }
]);

//ROTA PROCEDIMENTOS
$obRouter->get('/procedimentos', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Pages\Procedimentos::getProcedimentos($request));
    }
]);

//ROTA EQUIPAMENTOS
$obRouter->get('/equipamentos', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Pages\Equipamentos::getEquipamentos($request));
    }
]);