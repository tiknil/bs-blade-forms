<?php

namespace Tiknil\BsBladeForms;

use Illuminate\Support\ServiceProvider;

class BsBladeFormsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'bs-blade-forms');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bs-blade-forms');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        Blade::componentNamespace('Tiknil\\BsBladeForms\\Views\\Components', 'bs-form');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('bs-blade-forms.php'),
            ], 'bs-blade-forms:config');

            // Publishing the views.
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/bs-blade-forms'),
            ], 'bs-blade-forms:views');

            // Publishing assets.
            $this->publishes([
                __DIR__.'/../dist' => public_path('vendor/bs-blade-forms'),
            ], ['bs-blade-forms:assets', 'laravel-assets']);

            // Publishing the translation files.
            $this->publishes([
                __DIR__.'/../resources/lang' => lang_path('vendor/bs-blade-forms'),
            ], 'bs-blade-forms:lang');

            // Registering package commands.
            // $this->commands([]);
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
