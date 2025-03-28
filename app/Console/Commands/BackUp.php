<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BackUp extends Command
{
    protected $signature = 'backup:drive';
    protected $description = 'Backup database and files, then upload to Google Drive';

    public function handle()
    {
        $date = Carbon::now()->format('Y-m-d');
        $backupPath = storage_path("app/backup_{$date}.zip");

        $this->info("Creating backup...");
        $this->createBackup($backupPath);

        $accessToken = $this->token();
        if (!$accessToken) {
            $this->error('Failed to retrieve access token');
            return;
        }

        $rootFolderId = config('filesystems.disks.google.folder_id');
        $backupFolderId = $this->getOrCreateFolder('Backups', $rootFolderId, $accessToken);

        $this->info("Uploading backup to Google Drive...");
        $fileId = $this->uploadFileToDrive($backupPath, "backup_{$date}.zip", 'application/zip', $backupFolderId, $accessToken);

        if ($fileId) {
            $this->info("Backup successfully uploaded to Google Drive!");
            unlink($backupPath); // Delete local backup after upload
        } else {
            $this->error("Failed to upload backup.");
        }
    }

    private function createBackup($backupPath)
    {
        $zip = new ZipArchive();
        if ($zip->open($backupPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $this->error("Could not create ZIP archive");
            return;
        }
    
        // Path to SQLite database
        $sqliteDatabasePath = database_path('database.sqlite');
    
        if (file_exists($sqliteDatabasePath)) {
            $zip->addFile($sqliteDatabasePath, 'database.sqlite');
        } else {
            $this->error("SQLite database file not found!");
        }
    
        
    
        $zip->close();
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

    private function getOrCreateFolder($folderName, $parentFolderId, $accessToken)
    {
        $query = "name='{$folderName}' and mimeType='application/vnd.google-apps.folder' and '{$parentFolderId}' in parents and trashed=false";

        $searchResponse = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}"
        ])->get("https://www.googleapis.com/drive/v3/files", [
            'q' => $query,
            'fields' => 'files(id)'
        ]);

        $folders = $searchResponse->json()['files'] ?? [];
        if (!empty($folders)) {
            return $folders[0]['id'];
        }

        $createResponse = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type' => 'application/json'
        ])->post('https://www.googleapis.com/drive/v3/files', [
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$parentFolderId]
        ]);

        return $createResponse->json()['id'] ?? null;
    }

    private function uploadFileToDrive($filePath, $fileName, $mimeType, $folderId, $accessToken)
    {
        $metadata = [
            'name' => $fileName,
            'mimeType' => $mimeType,
            'parents' => [$folderId],
        ];

        $metadataResponse = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type' => 'application/json',
        ])->post('https://www.googleapis.com/upload/drive/v3/files?uploadType=resumable', $metadata);

        if (!$metadataResponse->successful()) {
            $this->error("Failed to initiate upload for {$fileName}");
            return null;
        }

        $uploadUrl = $metadataResponse->header('Location');
        $fileContent = file_get_contents($filePath);

        $uploadResponse = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type' => $mimeType,
        ])->withBody($fileContent, 'application/octet-stream')->put($uploadUrl);

        return $uploadResponse->json()['id'] ?? null;
    }
}
