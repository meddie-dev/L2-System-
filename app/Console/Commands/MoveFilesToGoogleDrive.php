<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MoveFilesToGoogleDrive extends Command
{
    protected $signature = 'move:files';
    protected $description = 'Move files to Google Drive daily';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $files = Storage::files('public/documents');

        foreach ($files as $file) {
            Storage::disk('google')->put(basename($file), Storage::get($file));
            Storage::delete($file);
        }

        $this->info('Files moved to Google Drive successfully!');
    }
}
