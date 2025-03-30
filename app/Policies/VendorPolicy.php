<?php

namespace App\Policies;

use App\Models\ActivityLogs;
use App\Models\Modules\Document;
use App\Models\Modules\Order;
use App\Models\Modules\VehicleReservation;
use App\Models\Payment;
use App\Models\User;

class VendorPolicy
{
    public function viewPdfOrder(User $user, Order $order): bool
    {
        if ($user->id !== $order->user_id) {
            ActivityLogs::create([
                'user_id' => $user->id,
                'event' => "Tried to view Unowned File at " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);
            return false;
        }
        return true;
    }

    public function viewPdfVehicleReservation(User $user, VehicleReservation $vehicleReservation): bool
    {
        if ($user->id !== $vehicleReservation->user_id) {
            ActivityLogs::create([
                'user_id' => $user->id,
                'event' => "Tried to view Unowned File at " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);
            return false;
        }
        return true;
    }

    public function viewOrder(User $user, Order $order): bool
    {
        if ($user->id !== $order->user_id) {
            ActivityLogs::create([
                'user_id' => $user->id,
                'event' => "Tried to view Unowned File at " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);
            return false;
        }
        return true;
    }

    public function viewDocument(User $user, Document $document): bool
    {
        if ($user->id !== $document->user_id) {
            ActivityLogs::create([
                'user_id' => $user->id,
                'event' => "Tried to view Unowned File at " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);
            return false;
        }
        return true;
    }

    
    public function viewPayment(User $user, Payment $payment): bool
    {
        if ($user->id !== $payment->user_id) {
            ActivityLogs::create([
                'user_id' => $user->id,
                'event' => "Tried to view Unowned File at " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);
            return false;
        }
        return true;
    }

    public function viewVehicleReservation(User $user, VehicleReservation $vehicleReservation): bool
    {
        if ($user->id !== $vehicleReservation->user_id) {
            ActivityLogs::create([
                'user_id' => $user->id,
                'event' => "Tried to view Unowned File at " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);
            return false;
        }
        return true;
    }
}
