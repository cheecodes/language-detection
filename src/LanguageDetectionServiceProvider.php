<?php

namespace CheeCodes\LanguageDetection;

use Illuminate\Support\ServiceProvider;

class LanguageDetectionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot() {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/language-detection.php' => config_path('language-detection.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register() {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/language-detection.php', 'language-detection');
    }
}
