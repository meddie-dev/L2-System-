<?php

namespace App\Models;

use App\Models\Modules\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class shipments extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'shipments';
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
