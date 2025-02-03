<?php

namespace App\Models\Modules;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;


use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory, Notifiable, HasRoles;
    
    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'password',
        'two_factor_enabled',
        'two_factor_code', 
        'two_factor_expires_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
