<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE CADASTRO DE UM NOVO AGENDAMENTO
$obRouter->get('/new-agendamento', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Agendamentos::getNewAgendamento($request));
    }
]);

//ROTA DE CADASTRO DE UM NOVO AGENDAMENTO (POST)
$obRouter->post('/new-agendamento', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Agendamentos::setNewAgendamento($request));
    }
]);

//ROTA DE EDIÇÃO DE UM AGENDAMENTO
$obRouter->get('/edit-agendamento={id}', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Agendamentos::getEditAgendamento($request, $id));
    }
]);

//ROTA DE EDIÇÃO DE UM AGENDAMENTO (POST)
$obRouter->post('/edit-agendamento={id}', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Agendamentos::setEditAgendamento($request, $id));
    }
]);

//ROTA DE ENVIO DE UM AGENDAMENTO
$obRouter->get('/env-agendamento={id}', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Agendamentos::setEnvAgendamento($request, $id));
    }
]);

//ROTA DE ATUALIZAÇÃO DE UM AGENDAMENTO
$obRouter->get('/atl-agendamento={id}', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Agendamentos::setAtlAgendamento($request, $id));
    }
]);

//ROTA DE EXCLUSÃO DE UM AGENDAMENTO
$obRouter->get('/delete-agendamento={id}', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Agendamentos::setDeleteAgendamento($request, $id));
    }
]);


