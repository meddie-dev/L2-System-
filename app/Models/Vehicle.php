<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Modules\VehicleReservation;
use App\Models\Fuel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Vehicle extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'vehicles';
    protected $guarded = [];

    // Driver
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicleReservations()
    {
        return $this->hasMany(VehicleReservation::class);
    }

    public function fuel()
    {
        return $this->hasMany(Fuel::class);
    }

    public function maintenance()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function tripTickets()
    {
        return $this->hasMany(TripTicket::class, 'vehicle_id', 'id');
    }

}
