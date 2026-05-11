<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        // Aquí registramos tus apodos para las rutas
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'no-cache' => \App\Http\Middleware\PreventBackHistory::class, // <--- GUARDÍAN ANTI-CACHÉ AGREGADO
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();