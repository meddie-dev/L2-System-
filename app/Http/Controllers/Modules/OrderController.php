<?php

namespace App\Http\Controllers\Modules;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActivityLogs;
use App\Models\Modules\Document;
use App\Models\Modules\Order;
use App\Models\User;
use App\Notifications\staff\staffApprovalRequest;
use App\Notifications\staff\staffApprovalStatus;
use App\Notifications\NewNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use PhpParser\Comment\Doc;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->user()->id)->get();
        return view('modules.vendor.order.index', compact('orders'));
    }

    public function create(User $user)
    {
        return view('modules.vendor.order.create', compact('user'));
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
            'total_amount' => 'required|string|min:0',
        ]);

        $checkOrderNumber = Order::where('orderNumber', $request->orderNumber)->first();
        if ($checkOrderNumber) {
            $orderNumber = strtoupper(Str::random(20));
        } else {
            $orderNumber = $request->orderNumber;
        }

        $order = Order::create([
            'user_id' => $user->id,
            'orderNumber' => $orderNumber,
            'pickupLocation' => $request->pickupLocation,
            'deliveryLocation' => $request->deliveryLocation,
            'deliveryDeadline' => $request->deliveryDeadline,
            'packageWeight' => $request->packageWeight,
            'specialInstructions' => $request->specialInstructions,
            'total_amount' => $request->total_amount,
            'assigned_to' => null,
            'approval_status' => 'pending',
            'approved_by' => null
        ]);

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Submitted Order Request: {$order->orderNumber} in time of: " . now('Asia/Manila')->format('Y-m-d H:i'),
            'ip_address' => $request->ip(),
        ]);

        // Get active staff members (last active within 5 minutes)
        $activeStaffs = User::role('Staff')->whereHas('roles', function ($query) {
            $query->where('name', 'Staff')->where('last_active_at', '>=', Carbon::now()->subMinutes(5));
        })->orderBy('last_active_at', 'desc')->limit(1)->get();

        // If no active staff found, get active admin instead
        if ($activeStaffs->count() == 0) {
            $activeStaffs = User::role('Admin')->get();

            foreach ($activeStaffs as $admin) {
                $order->assigned_to = $admin->id;
                $order->save();
                $admin->notify(new staffApprovalRequest('order', $order));
                $admin->notify(new NewNotification("Order request from {$user->firstName} {$user->lastName} with Order Number: ({$order->orderNumber}). Waiting for your approval."));
            }
        } else {
            // Send notification to active staff members
            foreach ($activeStaffs as $staff) {
                $order->assigned_to = $staff->id;
                $order->save();
                $staff->notify(new staffApprovalRequest('Order', $order));
                $staff->notify(new NewNotification("Order request from {$user->firstName} {$user->lastName} with Order Number: ({$order->orderNumber}). Waiting for your approval."));
            }
        }
        return redirect()->route('vendorPortal.order.document.new', ['order' => $order->id]);
    }
    
    public function edit(Order $order)
    {
        return view('modules.vendor.order.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'orderNumber' => 'required|string|max:255',
            'pickupLocation' => 'required|string|max:255',
            'deliveryLocation' => 'required|string|max:255',
            'deliveryDeadline' => 'required|date',
            'packageWeight' => 'required|numeric|min:0',
            'specialInstructions' => 'nullable|string|max:1000',
            'total_amount' => 'required|string|min:0',
        ]);

        $order->update([
            'orderNumber' => $request->orderNumber,
            'pickupLocation' => $request->pickupLocation,
            'deliveryLocation' => $request->deliveryLocation,
            'deliveryDeadline' => $request->deliveryDeadline,
            'packageWeight' => $request->packageWeight,
            'specialInstructions' => $request->specialInstructions,
            'total_amount' => $request->total_amount,
            'approval_status' => 'pending',
            'approved_by' => null
        ]);

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Update Order Request: {$order->orderNumber} in time of: " . now('Asia/Manila')->format('Y-m-d H:i'),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('vendorPortal.order.document.edit', ['order' => $order->id]);
    }

    public function checkApproved(Order $order)
    {
        return view('modules.order.order.approved', compact('order'));
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orderPortal.order')->with('success', 'Order request deleted successfully.');
    }

    // STAFF SECTION

    public function manage(Order $order) {
        $orders = order::where('assigned_to', Auth::id())->get();
        return view('modules.staff.vendor.manage', compact('orders'));
    }

    public function show(Order $order) {
        return view('modules.staff.vendor.show', compact('order'));
    }

    public function approve(Order $order) {
        $order->update(['approval_status' => 'approved']);

        $order->approved_by = Auth::id();
        $order->save();

        $order->notify(new staffApprovalStatus('Order', $order));

        return redirect()->route('staff.orders.manage')->with('success', 'Order approved successfully.');
    }
    public function reject(Order $order)
    {
        $order->update(['approval_status' => 'rejected']);

        $order->creator->notify(new staffApprovalStatus($order, 'rejected'));

        return redirect()->route('orders.index')->with('error', 'order rejected.');
    }

    
}
