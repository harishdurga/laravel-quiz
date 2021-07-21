<?php

namespace Harishdurga\LaravelQuiz;

use Illuminate\Support\ServiceProvider;

class LaravelQuizServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-quiz');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-quiz');
        // $this->loadMigrationsFrom(__DIR__ . '/../database/migrations', 'laravel-quiz');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-quiz.php'),
            ], 'config');
            $this->publishes([
                __DIR__ . '/../database/seeders/' => database_path('seeders/laravel-quiz'),
            ], 'seeds');
            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations/laravel-quiz'),
            ], 'migrations');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-quiz'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-quiz'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-quiz'),
            ], 'lang');*/

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
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-quiz');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-quiz', function () {
            return new LaravelQuiz;
        });
    }
}
