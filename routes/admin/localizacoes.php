<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE CADASTRO DE UMA NOVA LOCALIZAÇÃO
$obRouter->get('/new-localization', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Localizacoes::getNewLocal($request));
    }
]);

//ROTA DE CADASTRO DE UMA NOVA LOCALIZAÇÃO (POST)
$obRouter->post('/new-localization', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Localizacoes::setNewLocal($request));
    }
]);

//ROTA DE EDIÇÃO DE UM LOCAL
$obRouter->get('/edit-local={id}', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Localizacoes::getEditLocal($request, $id));
    }
]);

//ROTA DE EDIÇÃO DE UM LOCAL (POST)
$obRouter->post('/edit-local={id}', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Localizacoes::setEditLocal($request, $id));
    }
]);

//ROTA DE EXCLUSÃO DE UM LOCAL
$obRouter->get('/delete-local={id}', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Localizacoes::setDeleteLocal($request, $id));
    }
]);


