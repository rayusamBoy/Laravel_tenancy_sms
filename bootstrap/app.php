<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        using: function () {
            $centralDomains = config('tenancy.central_domains');

            foreach ($centralDomains as $domain) {
                Route::middleware('web')
                    ->domain($domain)
                    ->namespace('App\\Http\\Controllers')
                    ->group(base_path('routes/web.php'));
            }

            Route::middleware('web')
                ->namespace('App\\Http\\Controllers')
                ->group(base_path('routes/tenant.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'admin' => \App\Http\Middleware\Custom\Admin::class,
            'superAdmin' => \App\Http\Middleware\Custom\SuperAdmin::class,
            'teamSA' => \App\Http\Middleware\Custom\TeamSA::class,
            'headSA' => \App\Http\Middleware\Custom\HeadSA::class,
            'teamSAT' => \App\Http\Middleware\Custom\TeamSAT::class,
            'teamSATC' => \App\Http\Middleware\Custom\TeamSATC::class,
            'teamSATCL' => \App\Http\Middleware\Custom\TeamSATCL::class,
            'teamAccount' => \App\Http\Middleware\Custom\TeamAccount::class,
            'teamLibrary' => \App\Http\Middleware\Custom\TeamLibrary::class,
            'teamAdministrative' => \App\Http\Middleware\Custom\TeamAdministrative::class,
            'checkForPassUpdate' => \App\Http\Middleware\Custom\CheckForPasswordUpdate::class,
            'examIsLocked' => \App\Http\Middleware\Custom\ExamIsLocked::class,
            'myParent' => \App\Http\Middleware\Custom\MyParent::class,
            '2fa' => \PragmaRX\Google2FALaravel\Middleware::class,
            'itGuy' => \App\Http\Middleware\Custom\ITGuy::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
