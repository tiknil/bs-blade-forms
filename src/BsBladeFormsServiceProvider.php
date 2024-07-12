<?php

namespace Tiknil\BsBladeForms;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Tiknil\BsBladeForms\Console\PublishAssets;

class BsBladeFormsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'bs-blade-forms');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bs-blade-forms');

        Blade::componentNamespace('Tiknil\\BsBladeForms\\Components', 'bs');

        if ($this->app->runningInConsole()) {

            $this->commands([
                PublishAssets::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('bs-blade-forms.php'),
            ], 'bs-blade-forms:config');

            // Publishing the views.
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/bs-blade-forms'),
            ], 'bs-blade-forms:views');

            // Publishing assets.

            $this->publishes([
                __DIR__.'/../public/vendor/bs-blade-forms' => public_path('vendor/bs-blade-forms'),
            ], ['bs-blade-forms:views', 'laravel-assets']);

            // Publishing the translation files.
            $this->publishes([
                __DIR__.'/../resources/lang' => lang_path('vendor/bs-blade-forms'),
            ], 'bs-blade-forms:lang');

        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'bs-blade-forms');

        // Register the main class to use with the facade
        $this->app->singleton('bs-blade-forms', function () {
            return new BsBladeForms;
        });
    }
}
