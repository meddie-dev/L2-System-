<?php

namespace App\Models;

use App\Models\Modules\Order;
use App\Models\Modules\VehicleReservation;
use Illuminate\Database\Eloquent\Model;

class TripTicket extends Model
{
    protected $table = 'trip_tickets';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function incidentReport()
    {
        return $this->hasOne(IncidentReport::class);
    }

    public function vehicleReservation()
    {
        return $this->belongsTo(VehicleReservation::class);
    }

     public function vehicle()
     {
         return $this->belongsTo(Vehicle::class, 'vehicle_id', 'id');
     }
}
