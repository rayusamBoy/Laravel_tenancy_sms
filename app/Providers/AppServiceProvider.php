<?php

namespace App\Providers;

use App\Helpers\Qs;
use App\Listeners\TenantEventSubscriber;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        require base_path('routes/channels.php');

        Route::bind('id', function ($value) {
            return Qs::decodeHash($value);
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        Event::subscribe(TenantEventSubscriber::class);

        Paginator::useBootstrapFour();
    }
}
