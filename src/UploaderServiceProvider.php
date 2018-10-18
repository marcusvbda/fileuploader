<?php

namespace marcusvbda\uploader;

use Illuminate\Support\ServiceProvider;

class UploaderServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'uploader');

        $this->publishes([
            __DIR__.'/migrations' => database_path('migrations/'),
        ]);

        $this->publishes([
            __DIR__.'/config/uploader.php' => config_path('uploader.php')
        ], 'config');
    }

    public function register()
    {
        include __DIR__.'/routes.php';
        $this->app->make('marcusvbda\uploader\Controllers\UploaderController');
    }
}
