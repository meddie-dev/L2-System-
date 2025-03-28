<?php

namespace App\Http\Controllers;

use App\Models\ActivityLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
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

    public function listBackups()
    {
        $accessToken = $this->token();
        if (!$accessToken) {
            return response()->json(['error' => 'Failed to retrieve access token'], 500);
        }

        $query = "mimeType='application/zip' and trashed=false";
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}"
        ])->get("https://www.googleapis.com/drive/v3/files", [
            'q' => $query,
            'fields' => 'files(id, name, createdTime)'
        ]);

        return view('pages.backUp.list', ['backups' => $response->json()['files'] ?? []]);
    }

    public function restoreBackup(Request $request)
    {
        $fileId = $request->input('file_id');
        if (!$fileId) {
            return response()->json(['error' => 'Invalid backup file'], 400);
        }

        // Run the restore command with the selected file ID
        Artisan::call('backup:restore', ['file_id' => $fileId]);

        ActivityLogs::create([
            'user_id' => auth()->user()->id,
            'event' => 'Backup restored successfully at ' . now()->format('Y-m-d h:i A'),
            'ip_address' => request()->ip(),
        ]);

        return redirect()->back()->with('success', 'Backup restored successfully');
    }
}
