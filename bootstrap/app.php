<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SuperUserMiddleware;
use App\Http\Middleware\RedirectIfAuthenticatedCustom;
use App\Http\Middleware\LicencaMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->remove('guest');

        $middleware->alias([
            'superuser' => SuperUserMiddleware::class,
            'guest' => RedirectIfAuthenticatedCustom::class,
            'licenca' => LicencaMiddleware::class
        ]);


    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
