<?php

namespace App\Http\Controllers\Modules;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Modules\Vendor;
use App\Models\User;
use App\Notifications\staff\staffApprovalRequest;
use App\Notifications\staff\staffApprovalStatus;
use App\Notifications\admin\ApprovalRequest;
use App\Notifications\admin\ApprovalStatus;
use Illuminate\Support\Facades\Notification;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::all();
        return view('modules.vendors.index', compact('vendors'));
    }

    public function approve(Vendor $vendor)
    {
        if (auth()->user()->hasRole('Staff')) {
            $vendor->update([
                'approval_status' => 'on process',
                'approved_by' => auth()->id(),
            ]);

            $vendor->creator->notify(new staffApprovalStatus($vendor, 'on process'));
        } elseif (auth()->user()->hasRole('Admin')) {
            $vendor->update([
                'approval_status' => 'approved',
                'approved_by' => auth()->id(),
            ]);

            $vendor->creator->notify(new ApprovalStatus($vendor, 'approved'));
        }

        if (auth()->user()->hasRole('Staff')) {
            return redirect()->route('vendors.index')->with('success', 'Your request has been processed.');
        } elseif (auth()->user()->hasRole('Admin')) {
            return redirect()->route('vendors.index')->with('success', 'Vendor approved.');
        }
    }

    public function reject(Vendor $vendor)
    {
        $vendor->update(['approval_status' => 'rejected']);

        $vendor->creator->notify(new ApprovalStatus($vendor, 'rejected'));

        return redirect()->route('vendors.index')->with('error', 'Vendor rejected.');
    }
}
