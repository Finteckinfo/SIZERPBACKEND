<?php

namespace App\Providers;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\User;
use Illuminate\Support\Facades\URL;

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
        Schema::defaultStringLength(191);

        if (app()->environment('local')) {
            URL::forceRootUrl(config('app.url')); // e.g. http://localhost:8000
        }
        if (env('FORCE_HTTPS')) {
            URL::forceScheme('https');
        }

        if (Auth::check()) {
            View::share('loggedinuser', Auth::user());
            View::share('loggedinuserId', Auth::id());
            View::share('users', User::all());
        } else {
            View::share('loggedinuserId', null); // or handle accordingly
        }

    }
}
