<?php

namespace App\Models;

use App\Models\Modules\Order;
use App\Models\Modules\VehicleReservation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Payment extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'payments';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function vehicleReservation()
    {
        return $this->belongsTo(VehicleReservation::class);
    }
}
