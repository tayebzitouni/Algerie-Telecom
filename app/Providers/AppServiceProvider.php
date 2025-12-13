<?php

namespace App\Providers;

use Illuminate\Mail\MailManager;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use App\Mail\Transport\ResendTransport;


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

      
    }
}
