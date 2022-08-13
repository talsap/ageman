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

