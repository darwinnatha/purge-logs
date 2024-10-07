<?php

namespace Darwinnatha\PurgeLogs;

use Darwinnatha\PurgeLogs\Commands\PurgeLogsCommand;
use Illuminate\Support\ServiceProvider;

class PurgeLogsServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        // Enregistre la commande
        if ($this->app->runningInConsole()) {
            $this->commands([
                PurgeLogsCommand::class,
            ]);
            $this->registerConfig();
        }
    }

    protected function registerConfig()
    {
        if ($this->app->runningInConsole()) {

            $config = __DIR__ . '/../config/purge-logs.php';

            $this->publishes([$config => base_path('config/purge-logs.php')], ['purge-logs', 'purge-logs:config']);

            $this->mergeConfigFrom($config, 'purge-logs');
        }
    }
}
