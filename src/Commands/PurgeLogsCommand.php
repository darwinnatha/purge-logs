<?php

namespace Darwinnatha\PurgeLogs\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PurgeLogsCommand extends Command
{
    protected $signature = 'logs:purge {--keep-days=7 : Le nombre de jours pendant lequel les logs doivent être conservés}';
    protected $description = 'Purge les logs anciens en effaçant les lignes obsolètes';

    public function handle()
    {
        $logPath = storage_path('logs');

        $keepDays = (int) $this->option('keep-days') ?: config('purge-logs.retention_period');

        if (!File::exists($logPath)) {
            $this->error("The logs directory doesn't exists.");
            return Command::FAILURE;
        }

        $logFiles = File::files($logPath);
        $now = now();

        foreach ($logFiles as $logFile) {
            $this->info("Working on file : " . $logFile->getFilename());

            $lines = file($logFile->getPathname(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $newContent = [];
            $currentLogDate = null;

            foreach ($lines as $line) {
                //verify if the line contains a date (i.e [2024-10-05 12:00:00])
                if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $matches)) {
                    $logDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $matches[1]);

                    if ($logDate->diffInDays($now) > $keepDays) {
                        $currentLogDate = $logDate;
                        $this->info("Purge the log of : " . $logDate->toDateTimeString());
                        continue;
                    }

                    $currentLogDate = null;
                }

                if (!$currentLogDate) {
                    $newContent[] = $line;
                }
            }

            File::put($logFile->getPathname(), implode(PHP_EOL, $newContent));
            $this->info("Purge terminated for : " . $logFile->getFilename());
        }

        $this->info("Logs purge terminated");
        return Command::SUCCESS;
    }
}
