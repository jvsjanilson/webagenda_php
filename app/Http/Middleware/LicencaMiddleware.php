<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Licenca;

class LicencaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $today = Date('Y-m-d');
            $licenca = Licenca::orderBy('validade', 'desc')->first();

            if (!isset($licenca)){
                if (auth()->user()->superuser == 1) {
                    return redirect('/licenca');
                } else {
                    return redirect('/negado');
                }
            } else {
                if ($today > $licenca->validade) {
                    if (auth()->user()->superuser == 1) {
                        return redirect('/licenca');
                    } else {
                        return redirect('/negado');
                    }
                }
            }

        }

        return $next($request);
    }
}
