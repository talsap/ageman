<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA ORDEM
$obRouter->get('/ordens-servicos', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Ordem::getOrdem($request));
    }
]);

//ROTA RESPONSÁVEIS
$obRouter->get('/responsaveis', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Responsaveis::getResponsaveis($request));
    }
]);

//ROTA PROCEDIMENTOS
$obRouter->get('/procedimentos', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Procedimentos::getProcedimentos($request));
    }
]);

//ROTA EQUIPAMENTOS
$obRouter->get('/equipamentos', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Equipamentos::getEquipamentos($request));
    }
]);