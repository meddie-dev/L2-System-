<?php

namespace App\Models;

use App\Models\Modules\VehicleReservation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Fuel extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'fuels';
    protected $guarded = [];
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicleReservations()
    {
        return $this->belongsToMany(VehicleReservation::class);
    }
}
