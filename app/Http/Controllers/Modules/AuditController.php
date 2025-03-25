<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Jobs\Staff\Audit\NotifyIncidentApproved;
use App\Jobs\Staff\Audit\NotifyIncidentReview;
use App\Jobs\Vendor\SendDocumentNotifications;
use App\Jobs\Vendor\SendOrderNotifications;
use App\Jobs\Vendor\SendPaymentNotifications;
use App\Models\ActivityLogs;
use App\Models\IncidentReport;
use App\Models\Modules\Document;
use App\Models\Modules\Order;
use App\Models\Modules\VehicleReservation;
use App\Models\Payment;
use App\Models\TripTicket;
use App\Models\User;
use App\Notifications\NewNotification;
use App\Notifications\staff\staffApprovalStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditController extends Controller
{

    // Staff

    public function indexStaff()
    {
        $report = IncidentReport::where('assigned_to', Auth::id())->get();
        return view('modules.staff.audit.index', compact('report'));
    }

    public function detailsStaff(IncidentReport $incidentReport)
    {
        return view('modules.staff.audit.details', compact('incidentReport'));
    }

    public function reviewed(IncidentReport $incidentReport)
    {

        $incidentReport->update([
            'approval_status' => 'reviewed',
            'reviewed_by' => Auth::id(),
            'rejected_by' => null,
        ]);

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Reviewed Incident Report: {$incidentReport->reportNumber} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => request()->ip(),
        ]);

        // Dispatch notification asynchronously
        NotifyIncidentReview::dispatch($incidentReport);

        return redirect()->route('staff.audit.index')->with('success', 'Incident report reviewed successfully.');
    }

    public function rejected(IncidentReport $incidentReport)
    {
        $incidentReport->update([
            'approval_status' => 'rejected',
            'rejected_by' => Auth::id(),
        ]);

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Rejected Incident Report: {$incidentReport->reportNumber} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => request()->ip(),
        ]);

        $user = User::find($incidentReport->user_id);
        if ($user) {
            $user->notify(new staffApprovalStatus('Incident', $incidentReport));
            $user->notify(new NewNotification("Your incident report ({$incidentReport->reportNumber}) has been {$incidentReport->approval_status}."));
        }

        return redirect()->route('staff.audit.index')->with('success', 'Incident report rejected successfully.');
    }

    // Admin

    // Vendor
    public function indexAdmin()
    {
        $users = User::role('Vendor')->get();
        return view('modules.admin.vendor.manage', compact('users'));
    }

    public function showVendor(User $user)
    {
        $orders = Order::where('user_id', $user->id)->get();
        $payments = Payment::where('user_id', $user->id)->get();
        $documents = Document::where('user_id', $user->id)->get();
        $vehicleReservations = VehicleReservation::where('user_id', $user->id)->get();
        return view('modules.admin.vendor.show', compact('user', 'orders', 'payments', 'documents', 'vehicleReservations'));
    }

    public function showOrder(User $user, Order $order)
    {
        return view('modules.admin.vendor.order', compact('order', 'user'));
    }

    public function approvedOrder(User $user, Order $order)
    {
        $order->update([
            'approval_status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        if (is_null($order->reviewed_by)) {
            // Dispatch job asynchronously
            SendOrderNotifications::dispatch($order, $user);
        }

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Approved Order: {$order->orderNumber} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => request()->ip(),
        ]);

        $user = User::find($order->user_id);
        if ($user) {
            $user->notify(new staffApprovalStatus('Order', $order));
            $user->notify(new NewNotification("Your order ({$order->orderNumber}) has been {$order->approval_status}."));
        }

        return redirect()->route('admin.vendors.show', $user->id)->with('success', 'Order approved successfully.');
    }

    public function rejectedOrder(User $user, Order $order)
    {
        $order->update([
            'approval_status' => 'rejected',
            'rejected_by' => Auth::id(),
            'redirected_to' => null,
        ]);

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Rejected Order: {$order->orderNumber} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => request()->ip(),
        ]);

        $user = User::find($order->user_id);
        if ($user) {
            $user->notify(new staffApprovalStatus('Order', $order));
            $user->notify(new NewNotification("Your order ({$order->orderNumber}) has been {$order->approval_status}."));
        }

        return redirect()->route('admin.vendors.show', $user->id)->with('success', 'Order rejected successfully.');
    }

    public function showDocument(User $user, Document $document)
    {
        return view('modules.admin.vendor.document', compact('document', 'user'));
    }

    
    public function approvedDocument(User $user, Document $document)
    {
        $document->update([
            'approval_status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        if (is_null($document->reviewed_by)) {
            // Dispatch job asynchronously
            SendDocumentNotifications::dispatch($document, $user);
        }

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Approved Document: {$document->document_number} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => request()->ip(),
        ]);

        $user = User::find($document->user_id);
        if ($user) {
            $user->notify(new staffApprovalStatus('Document', $document));
            $user->notify(new NewNotification("Your document ({$document->document_number}) has been {$document->approval_status}."));
        }

        return redirect()->route('admin.vendors.show', $user->id)->with('success', 'Document approved successfully.');
    }

    public function rejectedDocument(User $user, Document $document)
    {
        $document->update([
            'approval_status' => 'rejected',
            'rejected_by' => Auth::id(),
            'redirected_to' => null,
        ]);

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Rejected Document: {$document->document_number} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => request()->ip(),
        ]);

        $user = User::find($document->user_id);
        if ($user) {
            $user->notify(new staffApprovalStatus('Document', $document));
            $user->notify(new NewNotification("Your document ({$document->document_number}) has been {$document->approval_status}."));
        }

        return redirect()->route('admin.vendors.show', $user->id)->with('success', 'Document rejected successfully.');
    }

    public function showPayment(User $user, Payment $payment)
    {
        return view('modules.admin.vendor.payment', compact('payment', 'user'));
    }

    public function approvedPayment(User $user, Payment $payment)
    {
        $payment->update([
            'approval_status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        if (is_null($payment->reviewed_by)) {
            // Dispatch job asynchronously
            SendPaymentNotifications::dispatch($payment, $user);
        }

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Approved Payment: {$payment->paymentNumber} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => request()->ip(),
        ]);

        $user = User::find($payment->user_id);
        if ($user) {
            $user->notify(new staffApprovalStatus('Payment', $payment));
            $user->notify(new NewNotification("Your payment ({$payment->paymentNumber}) has been {$payment->approval_status}."));
        }

        return redirect()->route('admin.vendors.show', $user->id)->with('success', 'Payment approved successfully.');
    }

    public function rejectedPayment(User $user, Payment $payment)
    {
        $payment->update([
            'approval_status' => 'rejected',
            'rejected_by' => Auth::id(),
            'redirected_to' => null,
        ]);

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Rejected Payment: {$payment->paymentNumber} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => request()->ip(),
        ]);

        $user = User::find($payment->user_id);
        if ($user) {
            $user->notify(new staffApprovalStatus('Payment', $payment));
            $user->notify(new NewNotification("Your payment ({$payment->paymentNumber}) has been {$payment->approval_status}."));
        }

        return redirect()->route('admin.vendors.show', $user->id)->with('success', 'Payment rejected successfully.');
    }

    public function showVehicleReservation(User $user, VehicleReservation $vehicleReservation)
    {
        return view('modules.admin.vendor.vehicleReservation', compact('vehicleReservation', 'user'));
    }

    public function approvedVehicleReservation(User $user, VehicleReservation $vehicleReservation)
    {
        $vehicleReservation->update([
            'approval_status' => 'approved',
            'approved_by' => Auth::id(),
        ]); 

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Approved Vehicle Reservation: {$vehicleReservation->reservationNumber} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => request()->ip(),
        ]);

        $user = User::find($vehicleReservation->user_id);
        if ($user) {
            $user->notify(new staffApprovalStatus('Vehicle Reservation', $vehicleReservation));
            $user->notify(new NewNotification("Your vehicle reservation ({$vehicleReservation->reservationNumber}) has been {$vehicleReservation->approval_status}."));
        }

        return redirect()->route('admin.vendors.show', $user->id)->with('success', 'Vehicle Reservation approved successfully.');
    }

    public function rejectedVehicleReservation(User $user, VehicleReservation $vehicleReservation)
    {
        $vehicleReservation->update([
            'approval_status' => 'rejected',
            'rejected_by' => Auth::id(),
            'redirected_to' => null,
        ]);

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Rejected Vehicle Reservation: {$vehicleReservation->reservationNumber} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => request()->ip(),
        ]); 

        $user = User::find($vehicleReservation->user_id);
        if ($user) {
            $user->notify(new staffApprovalStatus('Vehicle Reservation', $vehicleReservation));
            $user->notify(new NewNotification("Your vehicle reservation ({$vehicleReservation->reservationNumber}) has been {$vehicleReservation->approval_status}."));
        }

        return redirect()->route('admin.vendors.show', $user->id)->with('success', 'Vehicle Reservation rejected successfully.');
    }

    public function indexReportAdmin()
    {
        $report = IncidentReport::where('redirected_to', Auth::id())->get();
        return view('modules.admin.audit.index', compact('report'));
    }

    public function detailsAdmin(IncidentReport $incidentReport)
    {
        return view('modules.admin.audit.details', compact('incidentReport'));
    }

    public function approved(IncidentReport $incidentReport)
    {
        DB::beginTransaction();

        try {
            $incidentReport->update([
                'approval_status' => 'approved',
                'approved_by' => Auth::id(),
                'rejected_by' => null,
            ]);

            ActivityLogs::create([
                'user_id' => Auth::id(),
                'event' => "Reviewed Incident Report: {$incidentReport->reportNumber} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);

            DB::commit();

            // Dispatch notification asynchronously
            NotifyIncidentApproved::dispatch($incidentReport);

            return redirect()->route('admin.audit.index')->with('success', 'Incident report reviewed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    public function rejectedAdmin(IncidentReport $incidentReport)
    {
        DB::beginTransaction();

        try {
            $incidentReport->update([
                'approval_status' => 'rejected',
                'rejected_by' => Auth::id(),
            ]);

            ActivityLogs::create([
                'user_id' => Auth::id(),
                'event' => "Rejected Incident Report: {$incidentReport->reportNumber} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);

            DB::commit();

            $user = User::find($incidentReport->user_id);
            if ($user) {
                $user->notify(new staffApprovalStatus('Incident', $incidentReport->reportNumber));
                $user->notify(new NewNotification("Report Number ({$incidentReport->reportNumber}) has been {$incidentReport->approval_status}."));
            }

            return redirect()->route('admin.audit.index')->with('success', 'Incident report rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    public function indexActivity()
    {
        $users = User::role(['Staff', 'Driver'])->get();
        return view('modules.admin.audit.indexActivity', compact('users'));
    }

    public function staffActivity(User $user)
    {
        $logs = ActivityLogs::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

       
        $models = [Order::class, Payment::class, Document::class, VehicleReservation::class, IncidentReport::class];

        $pendingCounts = [];
        $reviewedCounts = [];
        $rejectedCounts = [];

        foreach ($models as $model) {
            $pendingCounts[] = $model::where('approval_status', 'pending')
                ->where('assigned_to', $user->id)
                ->count();

            $reviewedCounts[] = $model::where('approval_status', '!=', 'pending')
                ->where('assigned_to', $user->id)
                ->count();

            $rejectedCounts[] = $model::where('approval_status', 'rejected')
                ->where('assigned_to', $user->id)
                ->count();
        }

        $pendingCounts = array_sum($pendingCounts);
        $reviewedCounts = array_sum($reviewedCounts);
        $rejectedCounts = array_sum($rejectedCounts);

        return view('modules.admin.audit.staff', compact('user', 'logs','models', 'pendingCounts', 'reviewedCounts', 'rejectedCounts'));
    }

    public function driverActivity(User $user)
    {
        $logs = ActivityLogs::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

            $tripTicket = TripTicket::where('user_id', $user->id)->first(); 
        

        return view('modules.admin.audit.driver', compact('user', 'logs',  'tripTicket'));
    }
}
