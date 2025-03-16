<?php

namespace App\Models;

use App\Models\Modules\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $guarded = [];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products')
                    ->withPivot('id','name','quantity', 'price', 'weight')
                    ->withTimestamps();
    }
}
