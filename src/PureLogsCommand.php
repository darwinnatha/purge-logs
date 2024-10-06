<?php

namespace Darwinnatha\PurgeLogs;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PurgeLogsCommand extends Command
{
    protected $signature = 'logs:purge {--keep-days=7 : Le nombre de jours pendant lequel les logs doivent être conservés}';
    protected $description = 'Purge les logs anciens en effaçant les lignes obsolètes';

    public function handle()
    {
        // Récupération du chemin des logs
        $logPath = storage_path('logs');
        $keepDays = (int) $this->option('keep-days');

        // Vérification que le répertoire des logs existe
        if (!File::exists($logPath)) {
            $this->error("Le répertoire des logs n'existe pas.");
            return;
        }

        // Liste des fichiers logs dans le répertoire
        $logFiles = File::files($logPath);
        $now = now();

        foreach ($logFiles as $logFile) {
            $this->info("Traitement du fichier : " . $logFile->getFilename());
            
            // Lire le contenu du fichier
            $lines = file($logFile->getPathname(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $newContent = [];
            $currentLogDate = null;

            foreach ($lines as $line) {
                // Vérifier si la ligne contient une date (par exemple [2024-10-05 12:00:00])
                if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $matches)) {
                    // Si une date est trouvée, la convertir en objet Carbon
                    $logDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $matches[1]);

                    // Si le log date d'il y a plus de `keep-days` jours, on ne l'ajoute pas
                    if ($logDate->diffInDays($now) > $keepDays) {
                        $currentLogDate = $logDate;
                        $this->info("Purge du log datant du : " . $logDate->toDateTimeString());
                        continue; // Ignorer cette ligne et toutes les suivantes jusqu'au prochain log
                    }

                    // Sinon, mettre à jour la date courante et ajouter la ligne au contenu à conserver
                    $currentLogDate = null;
                }

                // Si aucune purge en cours, ajouter la ligne
                if (!$currentLogDate) {
                    $newContent[] = $line;
                }
            }

            // Réécrire le fichier avec le contenu mis à jour
            File::put($logFile->getPathname(), implode(PHP_EOL, $newContent));
            $this->info("Purge terminée pour : " . $logFile->getFilename());
        }

        $this->info("Purge des logs terminée.");
    }
}
