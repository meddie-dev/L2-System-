<?php

namespace App\Http\Controllers\Modules;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Staff\SendOrderApprovalNotification;
use App\Jobs\Vendor\SendOrderNotifications;
use App\Models\ActivityLogs;
use App\Models\Modules\Order;
use App\Models\User;
use App\Notifications\NewNotification;
use App\Notifications\staff\staffApprovalRequest;
use App\Notifications\staff\staffApprovalStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
            'product' => 'required|string|max:255',
            'quantity' => 'required|numeric|max:2000',
            'deliveryAddress' => 'required|string|max:255',
            'deliveryRequestDate' => 'required|date',
            'specialInstructions' => 'nullable|string|max:1000',
            'weight' => 'required|numeric|max:999999.99',
        ]);

        $orderNumber = Order::where('orderNumber', $request->orderNumber)->exists()
            ? strtoupper(Str::random(20))
            : $request->orderNumber;

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $user->id,
                'orderNumber' => $orderNumber,
                'product' => $request->product,
                'quantity' => $request->quantity,
                'weight' => $request->weight,
                'deliveryAddress' => $request->deliveryAddress,
                'deliveryRequestDate' => $request->deliveryRequestDate,
                'specialInstructions' => $request->specialInstructions,
                'approval_status' => 'pending',
                'reviewed_by' => null,
                'approved_by' => null,
                'rejected_by' => null,
                'assigned_to' => null,
                'redirected_to' => null
            ]);

            ActivityLogs::create([
                'user_id' => Auth::id(),
                'event' => "Submitted Order Request: {$order->orderNumber} at " . now('Asia/Manila')->format('Y-m-d H:i'),
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            // Dispatch the job asynchronously
            SendOrderNotifications::dispatch($order, $user);

            return redirect()->route('vendorPortal.order.document.new', ['order' => $order->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    public function edit(Order $order)
    {
        return view('modules.vendor.order.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'orderNumber' => 'required|string|max:255',
            'product' => 'required|string|max:255',
            'quantity' => 'required|string|max:255',
            'deliveryAddress' => 'required|string|max:255',
            'deliveryRequestDate' => 'required|string|max:255',
            'specialInstructions' => 'nullable|string|max:1000',
            'weight' => 'required|numeric|max:999999.99',
        ]);

        $order->update([
            'orderNumber' => $request->orderNumber,
            'product' => $request->product,
            'quantity' => $request->quantity,
            'deliveryAddress' => $request->deliveryAddress,
            'deliveryRequestDate' => $request->deliveryRequestDate,
            'specialInstructions' => $request->specialInstructions,
            'weight' => $request->weight,
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

    public function details(Order $order)
    {
        return view('modules.vendor.order.details', compact('order'));
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orderPortal.order')->with('success', 'Order request deleted successfully.');
    }

    // STAFF SECTION

    public function manage()
    {
        $orders = Order::where('assigned_to', Auth::id())->get();
        return view('modules.staff.vendor.manage', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('modules.staff.vendor.show', compact('order'));
    }

    public function approve(Order $order)
    {
        DB::beginTransaction();

        try {
            $order->update([
                'approval_status' => 'reviewed',
                'reviewed_by' => auth()->id(),
            ]);
    
            DB::commit();
    
            // Dispatch job asynchronously
            SendOrderApprovalNotification::dispatch($order);
    
            return redirect()->route('staff.vendors.manage')->with('success', 'Order reviewed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    public function reject(Order $order)
    {
        $order->update(['approval_status' => 'rejected']);

        $order->creator->notify(new staffApprovalStatus($order, 'rejected'));

        return redirect()->route('orders.index')->with('error', 'order rejected.');
    }
}
