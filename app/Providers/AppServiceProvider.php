<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        View::composer('layouts.navigation', function ($view): void {
            if (! Auth::check()) {
                $view->with([
                    'topbarNotifications' => collect(),
                    'topbarUnreadCount' => 0,
                ]);

                return;
            }

            $notifications = Notification::query()
                ->where('user_id', Auth::id())
                ->latest()
                ->take(10)
                ->get();

            $view->with([
                'topbarNotifications' => $notifications,
                'topbarUnreadCount' => $notifications->where('is_read', false)->count(),
            ]);
        });
    }
}
