<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;
use Symfony\Component\VarDumper\Caster\RedisCaster;

class MontadorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (auth()->check()) {
            if (auth()->user()->montador == 1) {
                $allowRoutes = [
                    'agendamontagens.index',
                    'agendamontagens.done',
                    'agendamontagens.entregue',
                    'agendamontagens.images',
                ];

                if (in_array(Route::currentRouteName(), $allowRoutes ))
                {
                    return $next($request);
                }
               return redirect()->route('agendamontagens.index');
            }
        }
        return $next($request);
    }
}
