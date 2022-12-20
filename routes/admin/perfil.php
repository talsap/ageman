<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE ACESSO AO PERFIL
/**$obRouter->get('/perfil', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Perfil::getPerfil($request));
    }
]);
*/