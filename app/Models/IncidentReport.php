<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IncidentReport extends Model
{
    use AuthorizesRequests;
    
    protected $table = 'incident_reports';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function tripTicket()
    {
        return $this->belongsTo(TripTicket::class);
    }
}
