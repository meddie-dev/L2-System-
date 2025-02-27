<?php

namespace App\Http\Controllers;

use App\Jobs\Staff\SendPaymentApprovalNotification;
use App\Jobs\Vendor\SendPaymentNotifications;
use App\Models\ActivityLogs;
use Illuminate\Http\Request;
use App\Models\Modules\Order;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\NewNotification;
use App\Notifications\staff\staffApprovalStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    // Vendor
    public function index()
    {
        $payments = Payment::where('user_id', auth()->user()->id)->get();
        return view('modules.vendor.payment.index', compact('payments'));
    }

    public function create(Order $order)
    {

        Order::findOrFail($order->id);
        return view('modules.vendor.payment.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        $user = auth()->user();

        $request->validate([
            'paymentNumber' => 'required|string|max:255|unique:payments',
            'paymentUrl' => 'required|mimes:pdf,doc,docx,png,jpg,jpeg',
        ]);
    
        DB::beginTransaction();
    
        try {
            $paymentNumber = $request->paymentNumber;
            $file = $request->file('paymentUrl');
            $path = $file->storeAs("payment/{$user->id}", $paymentNumber . '.' . $file->getClientOriginalExtension(), 'public');
    
            $payment = Payment::create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'paymentNumber' => $paymentNumber,
                'paymentUrl' => $path,
                'approval_status' => 'pending',
                'reviewed_by' => null,
                'approved_by' => null,
                'rejected_by' => null,
                'assigned_to' => null,
                'redirected_to' => null,
            ]);
    
            ActivityLogs::create([
                'user_id' => $user->id,
                'event' => "Payment Submitted with Payment Number: {$payment->paymentNumber} at " . now('Asia/Manila')->format('Y-m-d H:i'),
                'ip_address' => $request->ip(),
            ]);
    
            DB::commit();
    
            // Dispatch job asynchronously
            SendPaymentNotifications::dispatch($payment, $user);
    
            return redirect()->route('vendorPortal.order')->with('success', 'Order Request submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    public function edit(Order $order)
    {
        return view('modules.vendor.payment.edit', compact('order'));
    }

    public function update(Request $request, Payment $payment, Order $order)
    {
        $request->validate([
            'paymentNumber' => 'required|string|max:255',
            'paymentUrl' => 'required|mimes:pdf,doc,docx,png,jpg,jpeg',
        ]);

        $userId = auth()->user()->id;
        $paymentNumber = $request->paymentNumber;

        if ($request->hasFile('paymentUrl')) {
            $file = $request->file('paymentUrl');
            $path = $file->storeAs("payment/{$userId}", $paymentNumber . '.' . $file->getClientOriginalExtension(), 'public');

            if ($payment->paymentUrl) {
                Storage::disk('public')->delete($payment->paymentUrl);
            }

            $payment->paymentUrl = $path;
        }

        $payment->update([
            'paymentNumber' => $request->paymentNumber,
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

    // Staff

    public function manage()
    {
        $orders = Order::where('assigned_to', auth()->id())->get();
        return view('modules.staff.payment.manage', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('modules.staff.payment.show', compact('order'));
    }

    public function approve(Payment $payment)
    {
        DB::beginTransaction();

        try {
            $payment->update([
                'approval_status' => 'reviewed',
                'reviewed_by' => auth()->id(),
            ]);

            DB::commit();

            // Dispatch job asynchronously
            SendPaymentApprovalNotification::dispatch($payment);

            return redirect()->route('staff.payment.manage')->with('success', 'Payment reviewed successfully.');
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
