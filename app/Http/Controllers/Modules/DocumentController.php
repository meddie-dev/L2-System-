<?php

namespace App\Http\Controllers\Modules;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Staff\Document\SendDocumentApprovalNotification;
use App\Jobs\Vendor\SendDocumentNotifications;
use App\Models\ActivityLogs;
use App\Models\Modules\Document;
use App\Models\Modules\Order;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // Vendor
    public function index()
    {
        $documents = Document::where('user_id', auth()->user()->id)->get();
        return view('modules.vendor.document.index', compact('documents'));
    }

    public function create(Order $order)
    {

        return view('modules.vendor.document.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        $user = auth()->user();

        // Validate for multiple files
        $request->validate([
            'documentNumber' => 'required|string|max:255',
            'documentName' => 'required|string|max:255',
            'documentUrl' => 'required|mimes:pdf,doc,docx,png,jpg,jpeg',
        ]);
    
        DB::beginTransaction();
    
        try {
            $checkDocumentNumber = Document::where('documentNumber', $request->documentNumber)->first();
            $documentNumber = $checkDocumentNumber ? strtoupper(Str::random(20)) : $request->documentNumber;
    
            $userId = auth()->id();
            $file = $request->file('documentUrl');
            $path = $file->storeAs("documents/{$userId}", $documentNumber . '.' . $file->getClientOriginalExtension(), 'public');
    
            $document = Document::create([
                'order_id' => $order->id,
                'user_id' => $userId,
                'documentNumber' => $documentNumber,
                'documentName' => $request->documentName,
                'documentUrl' => $path,
                'approval_status' => 'pending',
                'reviewed_by' => null,
                'approved_by' => null,
                'rejected_by' => null,
                'assigned_to' => null,
                'redirected_to' => null,
            ]);
    
            ActivityLogs::create([
                'user_id' => Auth::id(),
                'event' => "Document Submitted with Document Number: {$document->documentNumber} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => $request->ip(),
            ]);
    
            DB::commit();
    
            // Dispatch job asynchronously
            SendDocumentNotifications::dispatch($document, $user);
    
            return redirect()->route('vendorPortal.order.payment.new', ['order' => $order->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    public function edit(Order $order)
    {
        return view('modules.vendor.document.edit', compact('order'));
    }

    public function update(Request $request, Document $document, Order $order)
    {
        $request->validate([
            'documentNumber' => 'required|string|max:255',
            'documentName' => 'required|string|max:255',
            'documentUrl' => 'required|mimes:pdf,doc,docx,png,jpg,jpeg',
        ]);


        $userId = auth()->user()->id;
        $documentNumber = $request->documentNumber;

        if ($request->hasFile('documentUrl')) {
            $file = $request->file('documentUrl');
            $path = $file->storeAs("documents/{$userId}", $documentNumber . '.' . $file->getClientOriginalExtension(), 'public');

            if ($document->documentUrl) {
                Storage::disk('public')->delete($document->documentUrl);
            }

            $document->documentUrl = $path;
        }

        $document->update([
            'documentNumber' => $request->documentNumber,
            'documentName' => $request->documentName,
        ]);

        ActivityLogs::create([
            'user_id' => auth()->id(),
            'event' => "Updated Document: {$document->documentNumber} in time of: " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('vendorPortal.order.payment.edit', ['order' => $order->id]);
    }

    public function details(Document $document)
    {
        return view('modules.vendor.document.details', compact('document'));
    }
    

    public function destroy(Document $document)
    {
        Storage::disk('public')->delete($document->documentUrl);
        $document->delete();
        return redirect()->route('vendorPortal.order.payment.edit', ['order' => $document->order_id]);
    }

    // Staff

    public function manage() 
    {
        $documents = Document::where('assigned_to', auth()->id())->get();
        return view('modules.staff.document.manage', compact('documents'));
    }

    public function show(Document $document) 
    {
        return view('modules.staff.document.show', compact('document'));
    }

    public function approve(Document $document)
    {
        DB::beginTransaction();

        try {
            $document->update([
                'approval_status' => 'reviewed',
                'reviewed_by' => auth()->id(),
                'rejected_by' => null
            ]);

            DB::commit();

            // Dispatch job asynchronously
            SendDocumentApprovalNotification::dispatch($document);

            return redirect()->route('staff.document.manage')->with('success', 'Document reviewed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }
    public function reject(Document $document)
    {
        DB::beginTransaction();

        try {
            $document->update([
                'approval_status' => 'rejected',
                'rejected_by' => auth()->id(),
                'redirected_to' => null
            ]);

            ActivityLogs::create([
                'user_id' => Auth::id(),
                'event' => "Rejected Document Request: {$document->documentNumber} in time of: " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);

            DB::commit();

            return redirect()->route('staff.document.manage')->with('success', 'Document rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    // Admin

    public function manageAdmin( Request $request) {
        $accessToken = $this->getAccessToken();
        $folderId = $request->get('folder_id', config('filesystems.disks.google.folder_id'));

        // Get the parent folder ID
        $parentFolderId = $this->getParentFolderId($folderId, $accessToken);

        // Fetch files in the current folder
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}"
        ])->get("https://www.googleapis.com/drive/v3/files", [
            'q' => "'{$folderId}' in parents and trashed=false",
            'fields' => 'files(id, name, mimeType, webViewLink, webContentLink)',
        ]);

        $files = $response->json()['files'] ?? [];
     
        return view('modules.admin.document.manage', compact('files', 'folderId','parentFolderId'));
    }

    private function getParentFolderId($folderId, $accessToken)
    {
        if ($folderId == config('filesystems.disks.google.folder_id')) {
            return null; // No parent folder (root folder)
        }

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}"
        ])->get("https://www.googleapis.com/drive/v3/files/{$folderId}", [
            'fields' => 'parents'
        ]);

        return $response->json()['parents'][0] ?? null;
    }


    private function getAccessToken()
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
}
