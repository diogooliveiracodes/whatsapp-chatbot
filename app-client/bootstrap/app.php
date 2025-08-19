<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CompanyActiveMiddleware;
use App\Http\Middleware\OwnerMiddleware;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\SubscriptionActiveMiddleware;
use App\Http\Middleware\UserActiveMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(SetLocale::class);
        $middleware->alias([
            'owner' => OwnerMiddleware::class,
            'admin' => AdminMiddleware::class,
            'company.active' => CompanyActiveMiddleware::class,
            'subscription.active' => SubscriptionActiveMiddleware::class,
            'user.active' => UserActiveMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
