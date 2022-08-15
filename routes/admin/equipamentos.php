<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE CADASTRO DE UM NOVO EQUIPAMENTO
$obRouter->get('/new-equipamento', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Equipamentos::getNewEquipamento($request));
    }
]);

//ROTA DE CADASTRO DE UM NOVO EQUIPAMENTO (POST)
$obRouter->post('/new-equipamento', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Equipamentos::setNewEquipamento($request));
    }
]);

//ROTA DE EDIÇÃO DE EQUIPAMENTO
$obRouter->get('/edit-equipamento={id}', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Equipamentos::getEditEquipamento($request, $id));
    }
]);

//ROTA DE EDIÇÃO DE EQUIPAMENTO (POST)
$obRouter->post('/edit-equipamento={id}', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Equipamentos::setEditEquipamento($request, $id));
    }
]);

//ROTA DE EXCLUSÃO DE UM EQUIPAMENTO
$obRouter->get('/delete-equipamento={id}', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Equipamentos::getDeleteEquipamento($request, $id));
    }
]);

//ROTA DE EXCLUSÃO DE UM EQUIPAMENTO (POST)
$obRouter->post('/delete-equipamento={id}', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Equipamentos::setDeleteEquipamento($request, $id));
    }
]);

