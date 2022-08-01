<?php

namespace App\Http\Middleware;

use \App\Session\Admin\Login as SessionAdminLogin;

class RequireAdminLogin{

    /**
     * MÉTODO RESPONSÁVEL POR EXECUTAR O MIDDLEWARE
     * @param Request $request
     * @param Clousure next
     * @return Response
     */
    public function handle($request, $next){
        //VERIFICA SE O USUÁRIO ESTÁ LOGADO
        if(!SessionAdminLogin::isLogged()){
            $request->getRouter()->redirect('/');
        }

        //VERIFICA SE O USUÁRIO ESTÁ LOGADO
        return $next($request);
    }
}