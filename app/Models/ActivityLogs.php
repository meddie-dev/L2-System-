<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityLogs extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
