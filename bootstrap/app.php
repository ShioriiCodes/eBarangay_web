<?php

use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureAdminOrStaff;
use App\Http\Middleware\EnsureResident;
use App\Http\Middleware\EnsureStaff;
use App\Http\Middleware\EnsureUserRole;
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
        // Use env() here, not config(): this closure runs during early console bootstrap
        // (e.g. composer package:discover) before the config service is registered.
        if (filter_var(env('TRUST_PROXIES', false), FILTER_VALIDATE_BOOLEAN)) {
            $middleware->trustProxies(at: '*');
        }

        $middleware->alias([
            'role' => EnsureUserRole::class,
            'admin' => EnsureAdmin::class,
            'staff' => EnsureStaff::class,
            'resident' => EnsureResident::class,
            'admin_or_staff' => EnsureAdminOrStaff::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
