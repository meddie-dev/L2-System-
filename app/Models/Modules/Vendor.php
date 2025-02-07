<?php

namespace App\Models\Modules;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Vendor extends Model
{
    use HasFactory, Notifiable, HasRoles;
    protected $title = 'vendor';
    
    protected $fillable = [
    'user_id',
    'orderNumber',
    'pickupLocation',
    'deliveryLocation',
    'deliveryDeadline',
    'packageWeight',
    'specialInstructions',
    'documentUpload',
    'approval_status',
    'approved_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isPending()
    {
        return $this->approval_status === 'pending';
    }
}
