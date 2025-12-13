<?php

namespace App\Providers;

use Illuminate\Mail\MailManager;
use Illuminate\Support\Facades\File;
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
        // Create storage/api-docs folder if not exists
        $path = storage_path('api-docs');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0775, true); // recursive = true
        }

        // Optional: extend MailManager for 'resend' mailer (requires custom transport)
        $this->app->make(MailManager::class)->extend('resend', function ($app) {
            // TODO: implement your custom Resend transport here
            // For now, just throw exception so it's clear it's not implemented
            throw new \Exception("Custom 'resend' mailer not implemented yet.");
        });
    }
}
