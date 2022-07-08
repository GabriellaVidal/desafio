<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Token
{
    /**
     * metodo Handle validando o token setado o authorization da request.
     * token contruido por email/password/time tudo na base64
     * validade do token = 2h
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     *
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if(!empty($response->headers->get('Authorization'))){
            $token_decode  = base64_decode(str_replace('Basic ', '', $response->headers->get('Authorization')));
            $dados =  explode('/', $token_decode);
            $validade  = Carbon::parse($dados[2]);

            if(now()->diff($validade)->h <= 2){
                $usuario = User::where([['email', $dados[0]], ['password', $dados[1]]])->first();

                if(!empty($usuario)){
                    return $response;
                }
            }
        }

        return response()->json(['error' => 'Unauthorized.'], 401);
    }
}
