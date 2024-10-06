<?php

namespace Darwinnatha\PurgeLogs;

use Darwinnatha\PurgeLogs\PurgeLogsCommand;
use Illuminate\Support\ServiceProvider;

class PurgeLogsServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Charge la configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/purge-logs.php', 'purge-logs');
    }

    public function boot()
    {
        // Enregistre la commande
        if ($this->app->runningInConsole()) {
            $this->commands([
                PurgeLogsCommand::class,
            ]);
        }

        // Publie le fichier de configuration
        $this->publishes([
            __DIR__ . '/../config/purge-logs.php' => config_path('purge-logs.php'),
        ], 'config');
    }
}
