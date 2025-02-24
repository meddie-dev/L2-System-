<?php

namespace App\Models\Modules;

use App\Models\Payment;
use App\Models\Shipments;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Order extends Model
{
    use HasFactory, Notifiable, HasRoles;
    
    protected $title = 'orders';
    
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function document()
    {
        return $this->hasMany(Document::class);
    }

    public function vehicleReservations()
    {
        return $this->hasMany(VehicleReservation::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    
    public function vehicleReservation()
    {
        return $this->hasOne(VehicleReservation::class);
    }

    public function shipment()
    {
        return $this->hasOne(Shipments::class);
    }

    public function isPending()
    {
        return $this->approval_status === 'pending';
    }
}
