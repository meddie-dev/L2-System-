<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Carbon\Carbon;
use DB;

class RestoreBackup extends Command
{
    protected $signature = 'backup:restore {file_id}';
    protected $description = 'Restore a selected backup from Google Drive';

    public function handle()
    {
        $fileId = $this->argument('file_id');
        if (!$fileId) {
            $this->error("No backup file specified.");
            return;
        }

        $accessToken = $this->token();
        $backupPath = storage_path("app/backup_restore.zip");

        $this->info("Downloading backup...");
        if (!$this->downloadFileFromDrive($fileId, $backupPath, $accessToken)) {
            $this->error("Failed to download backup.");
            return;
        }

        $this->info("Extracting backup...");
        $this->extractBackup($backupPath);

        $this->info("Restoring database...");
        $this->restoreDatabase();

        unlink($backupPath);
        $this->info("Backup restoration completed!");
    }

    private function token()
    {
        $client_id = config('services.google.client_id');
        $client_secret = config('services.google.client_secret');
        $refresh_token = config('services.google.refresh_token');

        $response = Http::post('https://oauth2.googleapis.com/token', [
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'refresh_token' => $refresh_token,
            'grant_type' => 'refresh_token',
        ]);

        return json_decode($response->getBody(), true)['access_token'] ?? null;
    }

    private function getFileIdFromDrive($fileName, $accessToken)
    {
        $query = "name='{$fileName}' and mimeType='application/zip' and trashed=false";

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}"
        ])->get("https://www.googleapis.com/drive/v3/files", [
            'q' => $query,
            'fields' => 'files(id)'
        ]);

        $files = $response->json()['files'] ?? [];
        return $files[0]['id'] ?? null;
    }

    private function downloadFileFromDrive($fileId, $savePath, $accessToken)
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}"
        ])->get("https://www.googleapis.com/drive/v3/files/{$fileId}?alt=media");

        if ($response->successful()) {
            file_put_contents($savePath, $response->body());
            return true;
        }

        return false;
    }

    private function extractBackup($backupPath)
    {
        $zip = new ZipArchive;
        if ($zip->open($backupPath) === true) {
            $zip->extractTo(storage_path('app/backup'));
            $zip->close();
        } else {
            $this->error("Failed to extract backup.");
            exit;
        }
    }

    private function restoreDatabase()
    {
        $dbPath = storage_path('app/backup/database.sqlite');

        if (file_exists($dbPath)) {
            copy($dbPath, database_path('database.sqlite'));
            $this->info("Database restored successfully.");
        } else {
            $this->error("Database file not found in backup.");
        }
    }
}
