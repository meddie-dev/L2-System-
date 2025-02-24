<?php

namespace App\Models\Modules;

use App\Models\Fuel;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class VehicleReservation extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'vehicle_reservations';
    protected $guarded = [];
    

    // Driver
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function fuel()
    {
        return $this->belongsToMany(Fuel::class, 'reservation_fuel');
    }
}
