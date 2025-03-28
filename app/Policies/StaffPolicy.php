<?php

namespace App\Policies;

use App\Models\ActivityLogs;
use App\Models\IncidentReport;
use App\Models\Maintenance;
use App\Models\Modules\Document;
use App\Models\Modules\Order;
use App\Models\Modules\VehicleReservation;
use App\Models\Payment;
use App\Models\User;

class StaffPolicy
{
    public function viewOrder(User $user, Order $order): bool
    {
        if ($user->id !== $order->assigned_to) {
            ActivityLogs::create([
                'user_id' => $user->id,
                'event' => "Tried to view Unassigned Task at " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);
            return false;
        }
        return true;
    }

    public function viewIncident(User $user, IncidentReport $incidentReport): bool
    {
        if ($user->id !== $incidentReport->assigned_to) {
            ActivityLogs::create([
                'user_id' => $user->id,
                'event' => "Tried to view Unassigned Task at " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);
            return false;
        }
        return true;
    }


    public function viewDocument(User $user, Document $document): bool
    {
        if ($user->id !== $document->assigned_to) {
            ActivityLogs::create([
                'user_id' => $user->id,
                'event' => "Tried to view Unassigned Task at  " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);
            return false;
        }
        return true;
    }

    public function viewPayment(User $user, Payment $payment): bool
    {
        if ($user->id !== $payment->assigned_to) {
            ActivityLogs::create([
                'user_id' => $user->id,
                'event' => "Tried to view Unassigned Task at  " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);
            return false;
        }
        return true;
    }

    public function viewVehicleReservation(User $user, VehicleReservation $vehicleReservation): bool
    {
        if ($user->id !== $vehicleReservation->assigned_to) {
            ActivityLogs::create([
                'user_id' => $user->id,
                'event' => "Tried to view Unassigned Task at  " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);
            return false;
        }
        return true;
    }
}
