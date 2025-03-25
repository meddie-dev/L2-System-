<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetReport extends Model
{
    protected $table = 'asset_reports';
    protected $guarded = [];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
