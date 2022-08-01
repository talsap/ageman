<?php

use \App\Http\Response;
use \App\Controller\Pages;

//ROTA LOGIN
$obRouter->get('/login', [
    function(){
        return new Response(200, Pages\Login::getLogin());
    }
]);

//ROTA RECUPERAR SENHA
$obRouter->get('/login/recuperar-senha', [
    function(){
        return new Response(200, Pages\Forgot::getForgot());
    }
]);

//ROTA DINÂMICA
$obRouter->get('/pagina/{idPagina}/{acao}', [
    function($idPagina,$acao){
        return new Response(200, 'Página '.$idPagina.' - '.$acao);
    }
]);