<?php

namespace Darwinnatha\PurgeLogs;

use Darwinnatha\PurgeLogs\Commands\PurgeLogsCommand;
use Illuminate\Support\ServiceProvider;

class PurgeLogsServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Charge la configuration
        $this->registerConfig();
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
        // $this->publishes([
        //     __DIR__ . '/../config/purge-logs.php' => config_path('purge-logs.php'),
        // ], 'config');
    }

    protected function registerConfig()
    {
        $config = __DIR__.'/../config/purge-logs.php';

        $this->publishes([$config => base_path('config/purge-logs.php')], 'config');

        $this->mergeConfigFrom($config, 'purge-logs');
    }
}
