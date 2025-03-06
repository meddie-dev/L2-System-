<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FleetCard extends Model
{
    protected $table = 'fleet_cards';

    protected $guarded = [];

    public function fuel()
    {
        return $this->hasMany(Fuel::class, 'fleet_card_id');
    }
}
