<?php

namespace App\Http\Controllers\Modules;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActivityLogs;
use App\Models\Modules\Vendor;
use App\Models\User;
use App\Notifications\staff\staffApprovalRequest;
use App\Notifications\staff\staffApprovalStatus;
use App\Notifications\NewNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;


class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::all();
        return view('modules.vendor.index', compact('vendors'));
    }

    public function create(User $user)
    {
        return view('modules.vendor.create', compact('user'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'orderNumber' => 'required|string|max:255',
            'pickupLocation' => 'required|string|max:255',
            'deliveryLocation' => 'required|string|max:255',
            'deliveryDeadline' => 'required|date',
            'packageWeight' => 'required|numeric|min:0',
            'specialInstructions' => 'nullable|string|max:1000',
            'documentUpload' => 'nullable|mimes:pdf,doc,docx,png,jpg,jpeg',
        ]);

        $checkOrderNumber = Vendor::where('orderNumber', $request->orderNumber)->first();
        if ($checkOrderNumber) {
            $orderNumber = strtoupper(Str::random(20));
        } else {
            $orderNumber = $request->orderNumber;
        }

        $documentPath = null;
        if ($request->hasFile('documentUpload')) {
            dd("File received:", $request->file('documentUpload')->getClientOriginalName());
        }

        $vendor = Vendor::create([
            'user_id' => $user->id,
            'orderNumber' => $orderNumber,
            'pickupLocation' => $request->pickupLocation,
            'deliveryLocation' => $request->deliveryLocation,
            'deliveryDeadline' => $request->deliveryDeadline,
            'packageWeight' => $request->packageWeight,
            'specialInstructions' => $request->specialInstructions,
            'documentUpload' => $documentPath,
            'assigned_to' => null,
            'approval_status' => 'pending',
            'approved_by' => null
        ]);

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Submitted Order Request: {$vendor->orderNumber} in time of: " . now('Asia/Manila')->format('Y-m-d H:i'),
            'ip_address' => $request->ip(),
        ]);

        // Get active staff members (last active within 5 minutes)
        $activeStaffs = User::whereHas('roles', function ($query) {
            $query->where('last_active_at', '>=', Carbon::now()->subMinutes(5));
        })->orderBy('last_active_at', 'desc')->limit(1)->get();

        // If no active staff found, get active admin instead
        if ($activeStaffs->count() == 0) {
            $activeStaffs = User::role('Admin')->get();

            foreach ($activeStaffs as $admin) {
                $vendor->assigned_to = $admin->id;
                $vendor->save();
                $admin->notify(new staffApprovalRequest('vendor', $vendor));
                $admin->notify(new NewNotification("Order request from {$user->firstName} {$user->lastName} with Order Number: ({$vendor->orderNumber}). Waiting for your approval."));
            }
        } else {
            // Send notification to active staff members
            foreach ($activeStaffs as $staff) {
                $vendor->assigned_to = $staff->id;
                $vendor->save();
                $staff->notify(new staffApprovalRequest('Order', $vendor));
                $staff->notify(new NewNotification("Order request from {$user->firstName} {$user->lastName} with Order Number: ({$vendor->orderNumber}). Waiting for your approval."));
            }
        }

        $user->notify(new NewNotification("Your order ({$vendor->orderNumber}) has been submitted. Please wait for approval."));
        return redirect()->route('vendorPortal.order')->with('success', 'Order request created successfully.');
    }

    public function edit(Vendor $vendor)
    {
        return view('modules.vendor.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'orderNumber' => 'required|string|max:255',
            'pickupLocation' => 'required|string|max:255',
            'deliveryLocation' => 'required|string|max:255',
            'deliveryDeadline' => 'required|date',
            'packageWeight' => 'required|numeric|min:0',
            'specialInstructions' => 'nullable|string|max:1000',
            'documentUpload' => 'nullable|mimes:pdf,doc,docx,png,jpg,jpeg',
        ]);

        $documentPath = null;
        if ($request->hasFile('documentUpload')) {
            dd("File received:", $request->file('documentUpload')->getClientOriginalName());
        }

        $vendor->update([
            'orderNumber' => $request->orderNumber,
            'pickupLocation' => $request->pickupLocation,
            'deliveryLocation' => $request->deliveryLocation,
            'deliveryDeadline' => $request->deliveryDeadline,
            'packageWeight' => $request->packageWeight,
            'specialInstructions' => $request->specialInstructions,
            'documentUpload' => $documentPath,
            'approval_status' => 'pending',
            'approved_by' => null
        ]);

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Update Order Request: {$vendor->orderNumber} in time of: " . now('Asia/Manila')->format('Y-m-d H:i'),
            'ip_address' => $request->ip(),
        ]);

        // Notification
        $user = User::where('id', $vendor->user_id)->first();
        $user->notify(new NewNotification("Order No.({$vendor->orderNumber}) has been updated. Please wait for approval."));

        return redirect()->route('vendorPortal.order')->with('success', 'Order request updated successfully.');
    }

    public function checkApproved(Vendor $vendor)
    {
        return view('modules.vendor.approved', compact('vendor'));
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('vendorPortal.order')->with('success', 'Order request deleted successfully.');
    }

    // STAFF SECTION

    public function manage(Vendor $vendor) {
        $vendors = Vendor::where('assigned_to', Auth::id())->get();
        return view('modules.staff.vendor.manage', compact('vendors'));
    }

    public function show(Vendor $vendor) {
        return view('modules.staff.vendor.show', compact('vendor'));
    }

    public function approve(Vendor $vendor) {
        $vendor->update(['approval_status' => 'approved']);

        $vendor->approved_by = Auth::id();
        $vendor->save();

        $vendor->notify(new staffApprovalStatus('Order', $vendor));

        return redirect()->route('staff.vendors.manage')->with('success', 'Order approved successfully.');
    }
    public function reject(Vendor $vendor)
    {
        $vendor->update(['approval_status' => 'rejected']);

        $vendor->creator->notify(new staffApprovalStatus($vendor, 'rejected'));

        return redirect()->route('vendors.index')->with('error', 'Vendor rejected.');
    }

    
}
