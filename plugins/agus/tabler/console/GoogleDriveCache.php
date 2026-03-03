<?php
namespace Agus\Tabler\Console;

use Illuminate\Console\Command;
use Agus\Tabler\Classes\GoogleDriveReader;

class GoogleDriveCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tabler:gdrive-cache {action? : warm|clear (default warm)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm or clear Google Drive file list cache';

    public function handle()
    {
        $action = $this->argument('action') ?: 'warm';
        if (!in_array($action, ['warm', 'clear'])) {
            $this->error("Action must be 'warm' or 'clear'");
            return 1;
        }

        if ($action === 'warm') {
            $this->info('Warming Google Drive cache...');
            GoogleDriveReader::warmCache();
            $this->info('Cache warmed.');
        } else {
            $this->info('Clearing Google Drive cache...');
            GoogleDriveReader::clearCache();
            $this->info('Cache cleared.');
        }

        return 0;
    }
}
