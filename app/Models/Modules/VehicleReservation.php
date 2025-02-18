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
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    // Driver
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fuel()
    {
        return $this->belongsToMany(Fuel::class, 'reservation_fuel');
    }
}
