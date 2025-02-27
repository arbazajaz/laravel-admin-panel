<?php

use Illuminate\Foundation\Application;
use Spatie\Permission\Middleware\RoleMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use BezhanSalleh\FilamentExceptions\FilamentExceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        App\Providers\AppServiceProvider::class,
        App\Providers\Filament\AdminPanelProvider::class,
        App\Providers\Filament\SupervisorPanelProvider::class,
        App\Providers\Filament\UserPanelProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web([
            // App\Http\Middleware\AddSeoDefaults::class,
        ]);

        // Register Spatie Role & Permission middleware
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        $middleware->redirectTo(fn () => Filament\Pages\Dashboard::getUrl());
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->reportable(fn (Throwable $e) => $exceptions->handler->shouldReport($e) &&
            FilamentExceptions::report($e)
        );
    })->create();
