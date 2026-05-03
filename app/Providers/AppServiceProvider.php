<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;

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
        $root = rtrim((string) config('app.url'), '/');
        $path = parse_url($root, PHP_URL_PATH) ?: '';
        if ($path !== '' && $path !== '/') {
            URL::forceRootUrl($root);
        }

        ResetPassword::toMailUsing(function (object $notifiable, string $token): MailMessage {
            $broker = config('auth.defaults.passwords');
            $minutes = (int) config("auth.passwords.{$broker}.expire", 60);

            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject(__('[:app] Reset your password', ['app' => config('app.name')]))
                ->line(__('You are receiving this email because someone requested a password reset for your :app account.', ['app' => config('app.name')]))
                ->action(__('Choose a new password'), $url)
                ->line(__('This link expires in :count minutes.', ['count' => $minutes]))
                ->line(__('If you did not request a password reset, you can ignore this email.'));
        });

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
