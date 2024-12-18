<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Observers\UserObserver;

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

        User::observe(UserObserver::class);

        View::composer('*', function ($view) {
            if (auth()->check()) {
                $unreadCount = auth()->user()->notifications()->wherePivot('is_read', false)->count();
                $view->with('unreadCount', $unreadCount);
            }
        });
        

        Paginator::useBootstrapFive();
    }
}
