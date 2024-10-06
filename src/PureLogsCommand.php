<?php

namespace Darwinnatha\PurgeLogs;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class PurgeLogsCommand extends Command
{
    protected $signature = 'logs:purge {days=30}';

    protected $description = 'Purge les fichiers de log plus anciens que le nombre de jours spécifié';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Récupère le nombre de jours depuis l'argument ou utilise la config
        $days = (int) $this->argument('days') ?: config('purge-logs.retention_period', 30);

        // Chemin vers le dossier des logs
        $logPath = storage_path('logs');

        if (!File::exists($logPath)) {
            $this->error('Le dossier de logs n\'existe pas.');
            return 1;
        }

        $files = File::files($logPath);
        $now = Carbon::now();

        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp(File::lastModified($file));

            if ($now->diffInDays($lastModified) > $days) {
                File::delete($file);
                $this->info("Fichier supprimé : {$file->getFilename()}");
            }
        }

        $this->info('Purge des logs terminée.');
        return 0;
    }
}
