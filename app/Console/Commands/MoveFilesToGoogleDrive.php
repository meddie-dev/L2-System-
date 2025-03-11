<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MoveFilesToGoogleDrive extends Command
{
    protected $signature = 'move:files';
    protected $description = 'Move files from local storage to Google Drive daily';

    public function handle()
    {
        $year = Carbon::now()->format('Y');
        $month = Carbon::now()->format('F');
        $day = Carbon::now()->format('d');

        $localBasePath = 'public'; // Ensuring correct path
        $this->info("Local base path: {$localBasePath}");

        $accessToken = $this->token();
        if (!$accessToken) {
            $this->error('Failed to retrieve access token');
            return;
        }

        $rootFolderId = config('filesystems.disks.google.folder_id');
        $yearFolderId = $this->getOrCreateFolder($year, $rootFolderId, $accessToken);
        $monthFolderId = $this->getOrCreateFolder($month, $yearFolderId, $accessToken);
        $dayFolderId = $this->getOrCreateFolder($day, $monthFolderId, $accessToken);

        $this->moveFolderToDrive($localBasePath, $dayFolderId, $accessToken);

        $this->info('Files moved to Google Drive successfully!');
    }

    private function moveFolderToDrive($localPath, $parentFolderId, $accessToken)
    {
        $directories = Storage::directories($localPath);
        $files = Storage::files($localPath);

        $this->info("Processing path: {$localPath}");
        $this->info("Directories: " . json_encode($directories));
        $this->info("Files: " . json_encode($files));

        $excludedFiles = ['.gitignore']; // Add files to exclude here

        foreach ($directories as $directory) {
            $folderName = basename($directory);
            $this->info("Processing folder: {$folderName}");
            $folderId = $this->getOrCreateFolder($folderName, $parentFolderId, $accessToken);
            $this->moveFolderToDrive($directory, $folderId, $accessToken);
        }

        foreach ($files as $file) {
            $fileName = basename($file);
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

            if (in_array($fileName, $excludedFiles)) {
                $this->info("Skipping excluded file: {$fileName}");
                continue;
            }

            $mimeType = Storage::mimeType($file);
            $fileContent = Storage::get($file);

            if (!$fileContent) {
                $this->error("Failed to read file: {$fileName}");
                continue;
            }

            $this->info("Uploading file: {$fileName} from path: {$file}");
            $fileId = $this->uploadFileToDrive($fileContent, $fileName, $mimeType, $parentFolderId, $accessToken);

            if ($fileId) {
                $this->info("Successfully uploaded: {$fileName}");
                Storage::delete($file);
                $this->info("Deleted local file: {$fileName}");
            } else {
                $this->error("Failed to upload: {$fileName}");
            }
        }
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

    private function uploadFileToDrive($fileContent, $fileName, $mimeType, $folderId, $accessToken)
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
        $uploadResponse = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type' => $mimeType,
        ])->withBody($fileContent, 'application/octet-stream')->put($uploadUrl);

        if (!$uploadResponse->successful()) {
            $this->error("File upload failed: " . $uploadResponse->body());
        }

        return $uploadResponse->json()['id'] ?? null;
    }
}