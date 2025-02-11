<?php

namespace App\Http\Controllers;

use App\Models\ActivityLogs;
use Illuminate\Http\Request;
use App\Models\Modules\Order;
use App\Models\Payment;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\NewNotification;
use App\Notifications\staff\staffApprovalRequest;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::where('user_id', auth()->user()->id)->get();
        return view('modules.vendor.payment.index', compact('payments'));
    }

    // VENDOR/ORDER SECTION

    public function create(Order $order, User $user)
    {

        Order::findOrFail($order->id);
        return view('modules.vendor.payment.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        $user = auth()->user();

        $request->validate([
            'paymentNumber' => 'required|string|max:255',
            'paymentMethod' => 'required|string',
            'amount' => 'required|string|min:0',
        ]);

        $checkPaymentNumber = Payment::where('paymentNumber', $request->paymentNumber)->first();
        if ($checkPaymentNumber) {
            $paymentNumber = strtoupper(Str::random(20));
        } else {
            $paymentNumber = $request->paymentNumber;
        }

        $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' => auth()->user()->id,
            'paymentNumber' => $paymentNumber,
            'paymentMethod' => $request->paymentMethod,
            'amount' => $request->amount,
            'paymentStatus' => 'pending',
        ]);

        // Get active staff members (last active within 5 minutes)
        $activeStaffs = User::role('Staff')->whereHas('roles', function ($query) {
            $query->where('name', 'Staff')->where('last_active_at', '>=', Carbon::now()->subMinutes(5));
        })->orderBy('last_active_at', 'desc')->limit(1)->get();

        // If no active staff found, get active admin instead
        if ($activeStaffs->count() == 0) {
            $activeStaffs = User::role('Admin')->get();

            foreach ($activeStaffs as $admin) {
                $payment->assigned_to = $admin->id;
                $payment->save();
                $admin->notify(new staffApprovalRequest('Payment', $payment));
                $admin->notify(new NewNotification("Payment by {$user->firstName} {$user->lastName} with Payment Number: ({$payment->paymentNumber}). Waiting for your approval."));
            }
        } else {
            // Send notification to active staff members
            foreach ($activeStaffs as $staff) {
                $payment->assigned_to = $staff->id;
                $payment->save();
                $staff->notify(new staffApprovalRequest('Payment', $payment));
                $staff->notify(new NewNotification("Payment by {$user->firstName} {$user->lastName} with Payment Number: ({$payment->paymentNumber}). Waiting for your approval."));
            }
        }

        ActivityLogs::create([
            'user_id' => auth()->id(),
            'event' => "Payment Submitted with Payment Number: {$payment->paymentNumber} in time of: " . now('Asia/Manila')->format('Y-m-d H:i'),
            'ip_address' => $request->ip(),
        ]);

        $user->notify(new NewNotification("Your order ({$order->orderNumber}) has been submitted. Please wait for approval."));
        return redirect()->route('vendorPortal.order')->with('success', 'Order Request submitted successfully.');
    }

    public function edit(Order $order)
    {
        return view('modules.vendor.payment.edit', compact('order'));
    }

    public function update(Request $request, Payment $payment , Order $order)
    {
        $request->validate([
            'paymentNumber' => 'required|string|max:255',
            'paymentMethod' => 'required|string',
            'amount' => 'required|string|min:0',
        ]);

        $payment->update([
            'paymentNumber' => $request->paymentNumber,
            'paymentMethod' => $request->paymentMethod,
            'amount' => $request->amount,
        ]);

        ActivityLogs::create([
            'user_id' => auth()->id(),
            'event' => "Payment Updated with Payment Number: {$payment->paymentNumber} in time of: " . now('Asia/Manila')->format('Y-m-d H:i'),
            'ip_address' => $request->ip(),
        ]);

        // Notification
        $user = User::where('id', $order->user_id)->first();
        $user->notify(new NewNotification("Order No.({$order->orderNumber}) has been updated. Please wait for approval."));

        return redirect()->route('vendorPortal.order')->with('success', 'Order Request updated successfully.');
    }

    public function details(Payment $payment)
    {
        return view('modules.vendor.payment.details', compact('payment'));
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('vendorPortal.order')->with('success', 'Order Request deleted successfully.');
    }


}
