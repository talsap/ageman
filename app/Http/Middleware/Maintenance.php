<?php


namespace App\Http\Middleware;


class Maintenance{
    /**
     * MÉTODO RESPONSÁVEL POR EXECUTAR O MIDDLEWARE
     * @param Request $request
     * @param Clousure next
     * @return Response
     */
    public function handle($request, $next){
        //VERIFICA O ESTADO DE MANTUNÇÃO DA PÁGINA
        if(getenv('MAINTENANCE') == 'true'){
            throw new \Exception("Página em manutenção. Tente novamente mais tarde.", 200);

        }

        //EXECUTA O PRÓXIMO NÍVEL DO MIDDLEWARE
        return $next($request);
    }


}