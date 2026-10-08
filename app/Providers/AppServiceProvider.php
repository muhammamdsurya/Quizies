<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Selama App Password Gmail (MAIL_PASSWORD) belum diisi, email ditulis ke storage/logs/laravel.log
        // agar fitur seperti reset password tidak error. OTP otomatis nonaktif dalam kondisi ini.
        if (config('mail.default') === 'smtp' && blank(config('mail.mailers.smtp.password'))) {
            config([
                'mail.default' => 'log',
                'mail.from.address' => config('mail.from.address') ?: 'noreply@kuiz.test',
            ]);
        }
    }
}
