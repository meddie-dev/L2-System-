<?php

namespace App\Http\Controllers\Modules;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActivityLogs;
use App\Models\Modules\Document;
use App\Models\Modules\Order;
use App\Models\Payment;
use App\Notifications\NewNotification;
use App\Notifications\staff\staffApprovalRequest;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\staff\staffApprovalStatus;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::where('user_id', auth()->user()->id)->get();
        return view('modules.vendor.document.index', compact('documents'));
    }

    // VENDOR/ORDER SECTION

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


        $checkDocumentNumber = Document::where('documentNumber', $request->documentNumber)->first();
        if ($checkDocumentNumber) {
            $documentNumber = strtoupper(Str::random(20));
        } else {
            $documentNumber = $request->documentNumber;
        }

        $userId = auth()->id();
        $file = $request->file('documentUrl');
        $path = $file->storeAs("documents/{$userId}", $documentNumber . '.' . $file->getClientOriginalExtension(), 'public');

        $document = Document::create([
            'order_id' => $order->id,
            'user_id' => $userId,
            'documentUrl' => $path,
            'documentNumber' => $documentNumber,
            'documentName' => $request->documentName,
        ]);

        // Get active staff members (last active within 5 minutes)
        $activeStaffs = User::role('Staff')->whereHas('roles', function ($query) {
            $query->where('name', 'Staff')->where('last_active_at', '>=', Carbon::now()->subMinutes(5));
        })->orderBy('last_active_at', 'desc')->limit(1)->get();

        // If no active staff found, get active admin instead
        if ($activeStaffs->count() == 0) {
            $activeStaffs = User::role('Admin')->get();

            foreach ($activeStaffs as $admin) {
                $document->assigned_to = $admin->id;
                $document->save();
                $admin->notify(new staffApprovalRequest('Document', $document));
                $admin->notify(new NewNotification("Document Uploaded by {$user->firstName} {$user->lastName} with Document Number: ({$document->documentNumber}). Waiting for your approval."));
            }
        } else {
            // Send notification to active staff members
            foreach ($activeStaffs as $staff) {
                $document->assigned_to = $staff->id;
                $document->save();
                $staff->notify(new staffApprovalRequest('Document', $document));
                $staff->notify(new NewNotification("Document Uploaded by {$user->firstName} {$user->lastName} with Document Number: ({$document->documentNumber}). Waiting for your approval."));
            }
        }

        ActivityLogs::create([
            'user_id' => auth()->id(),
            'event' => "Uploaded Document: {$document->documentNumber} in time of: " . now('Asia/Manila')->format('Y-m-d H:i'),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('vendorPortal.order.payment.new', ['order' => $order->id]);
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
            'event' => "Updated Document: {$document->documentNumber} in time of: " . now('Asia/Manila')->format('Y-m-d H:i'),
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

    // STAFF SECTION

    public function manage() {
        $orders = Order::where('assigned_to', auth()->id())->get();
        return view('modules.staff.document.manage', compact('orders'));
    }

    public function show(Order $order) {
        return view('modules.staff.document.show', compact('order'));
    }

    public function approve(Document $document) {
        $document->update(['approval_status' => 'approved']);

        $document->approved_by = auth()->id();
        $document->save();

        $document->notify(new staffApprovalStatus('Document', $document));

        return redirect()->route('staff.document.manage')->with('success', 'Order approved successfully.');
    }
    public function reject(Order $order)
    {
        $order->update(['approval_status' => 'rejected']);

        $order->creator->notify(new staffApprovalStatus($order, 'rejected'));

        return redirect()->route('orders.index')->with('error', 'order rejected.');
    }

    // Admin

    public function indexAdmin() {
        $users = User::role('Vendor')->whereHas('document', function ($query) {
            $query->where('approval_status', 'approved');
        })->get();
        return view('modules.admin.document.manage', compact('users'));
    }
}
