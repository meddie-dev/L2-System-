<?php

namespace App\Http\Controllers;

use App\Jobs\Staff\Payment\SendPaymentRejectionNotification;
use App\Jobs\Staff\Payment\SendPaymentApprovalNotification;
use App\Jobs\Vendor\SendPaymentNotifications;
use App\Models\ActivityLogs;
use Illuminate\Http\Request;
use App\Models\Modules\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Str;
use App\Notifications\NewNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            'paymentNumber' => 'required|string|max:255|unique:payments,paymentNumber',
            'date' => 'required|date',
            'due_date' => 'required|date',
            'account_number' => 'required|string|max:255',
            'total_amount_due' => 'required|numeric|between:0,9999999999.99',
        ]);

        $paymentNumber = Payment::where('paymentNumber', $request->paymentNumber)->exists()
            ? strtoupper(Str::random(20))
            : $request->paymentNumber;

        $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'paymentNumber' => $paymentNumber,
            'date' => $request->date,
            'due_date' => $request->due_date,
            'account_number' => $request->account_number,
            'total_amount_due' => $request->total_amount_due,
            'approval_status' => 'pending',
        ]);

        ActivityLogs::create([
            'user_id' => $user->id,
            'event' => "Payment Submitted with Payment Number: {$payment->paymentNumber} at " . now('Asia/Manila')->format('Y-m-d H:i'),
            'ip_address' => $request->ip(),
        ]);


        // Dispatch job asynchronously
        SendPaymentNotifications::dispatch($payment, $user);

        return redirect()->route('vendorPortal.order')->with('success', 'Order Request submitted successfully.');
    }

    public function edit(Order $order)
    {
        return view('modules.vendor.payment.edit', compact('order'));
    }

    public function update(Request $request, Payment $payment, Order $order)
    {
        $user = auth()->user();
        
        $request->validate([
            'paymentNumber' => 'required|string|max:255',
            'date' => 'required|date',
            'due_date' => 'required|date',
            'account_number' => 'required|string|max:255',
            'total_amount_due' => 'required|numeric|between:0,9999999999.99',
        ]);

        $payment->update([
            'paymentNumber' => $request->paymentNumber,
            'date' => $request->date,
            'due_date' => $request->due_date,
            'account_number' => $request->account_number,
            'total_amount_due' => $request->total_amount_due,
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

    public function paymentPdf(Order $order)
    {
        $order = Order::findOrFail($order->id);

        // Define the filename and storage path
        $filename = "invoice-{$order->payment->paymentNumber}.pdf";
        $folderPath = "payments/invoices/{$order->payment->id}/";
        $fullPath = "public/{$folderPath}{$filename}";

        // Ensure directory exists
        Storage::disk('public')->makeDirectory($folderPath, 0755, true, true);

        // Check if an existing PDF file needs to be deleted
        if (Storage::disk('public')->exists($folderPath . $filename)) {
            Storage::disk('public')->delete($folderPath . $filename);
        }

        // Generate PDF
        $pdf = Pdf::loadView('pdf.invoice', compact('order'));

        // Store PDF in public disk
        Storage::disk('public')->put($folderPath . $filename, $pdf->output());

        // Log for debugging
        Log::info("PDF saved at: " . storage_path("app/public/{$folderPath}{$filename}"));

        return $pdf->stream($filename);
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
                'rejected_by' => null
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

    public function reject(Payment $payment)
    {
        DB::beginTransaction();

        try {
            $payment->update([
                'approval_status' => 'rejected',
                'rejected_by' => auth()->id(),
                redirected_to => null
            ]);

            SendPaymentRejectionNotification::dispatch($payment);

            ActivityLogs::create([
                'user_id' => Auth::id(),
                'event' => "Rejected Payment Request: {$payment->paymentNumber} in time of: " . now('Asia/Manila')->format('Y-m-d H:i'),
                'ip_address' => request()->ip(),
            ]);

            DB::commit();

            return redirect()->route('staff.payment.manage')->with('success', 'Payment rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }
}
