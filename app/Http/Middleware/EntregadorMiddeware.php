<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class EntregadorMiddeware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            if (auth()->user()->entregador == 1) {
                $allowRoutes = [
                    'agendas.index',
                    'agendas.done',
                ];

                if (in_array(Route::currentRouteName(), $allowRoutes ))
                {
                    return $next($request);
                }
               return redirect()->route('agendas.index');
            }
        }
        return $next($request);
    }
}
