<?php

use App\Http\Middleware\CheckTokenExpiry;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Middleware\HandleCors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'token.expiry' => CheckTokenExpiry::class,
            'role.admin' => \App\Http\Middleware\AdminMiddleware::class,
            'role.user' => \App\Http\Middleware\NormalUserMiddleware::class,
        ]);
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->statefulApi();

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->validateCsrfTokens([
            'api/*',
            'sanctum/csrf-cookie',
            'login',
            'logout',
            // 'me'
        ]);

        $middleware->prepend(HandleCors::class);

        $middleware->api(append: [
            'auth:sanctum',
            // 'token.expiry',

            // 'throttle:api',
            // 'bindings',
        ]);


    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
