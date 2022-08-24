<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE CADASTRO DE UM NOVO RESPONSÁVEL
$obRouter->get('/new-responsible', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Responsaveis::getNewResponsible($request));
    }
]);

//ROTA DE CADASTRO DE UM NOVO RESPONSÁVEL (POST)
$obRouter->post('/new-responsible', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Responsaveis::setNewResponsible($request));
    }
]);

//ROTA DE EDIÇÃO DE UM RESPONSÁVEL
$obRouter->get('/edit-responsible={id}', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Responsaveis::getEditResponsible($request, $id));
    }
]);

//ROTA DE EDIÇÃO DE UM RESPONSÁVEL (POST)
$obRouter->post('/edit-responsible={id}', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Responsaveis::setEditResponsible($request, $id));
    }
]);

//ROTA DE EXCLUSÃO DE UM RESPONSÁVEL
$obRouter->get('/delete-responsible={id}', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Responsaveis::setDeleteResponsible($request, $id));
    }
]);


