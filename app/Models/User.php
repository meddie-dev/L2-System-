<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Modules\Document;
use App\Models\Modules\Order;
use App\Models\Modules\VehicleReservation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Modules\Vendor;
use Carbon\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

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

    public function isOnline()
    {
        return $this->last_active_at && $this->last_active_at->gt(Carbon::now()->subMinutes(5));
    }

    public function activity_logs()
    {
        return $this->hasMany(ActivityLogs::class);
    }

    // MODULES
    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    public function document()
    {
        return $this->hasMany(Document::class);
    }

    public function vehicles()
    {
        // return $this->hasMany(Vehicle::class);
    }

    public function vehicleReservations()
    {
        return $this->hasMany(VehicleReservation::class);
    }

    public function tripTickets()
    {
        return $this->hasMany(TripTicket::class);
    }

    public function calculatePerformance()
    {
        $totalDeliveries = $this->tripTickets()->count();
        $onTime = $this->on_time_deliveries ?? 0;
        $late = $this->late_deliveries ?? 0;
        $early = $this->early_deliveries ?? 0;

        // Handle ratings better
        $rating = $this->tripTickets()->whereNotNull('rating')->avg('rating') ?? 3.5; // Default to neutral score

        // Scoring calculation
        $ratingScore = ($rating / 5) * 50; // 50% weight

        if ($totalDeliveries > 0) {
            $onTimeScore = ($onTime / $totalDeliveries) * 30; // 30% weight
            $lateScore = max(0, (1 - ($late / $totalDeliveries)) * 20); // 20% weight
            $earlyScore = min(($early / $totalDeliveries) * 10, 10); // 10% weight
        } else {
            $onTimeScore = 15; // Neutral
            $lateScore = 5; // Slight penalty
            $earlyScore = 0;
        }

        $this->performance_score = round($ratingScore + $onTimeScore + $lateScore + $earlyScore, 2);
        $this->save();
    }

    public function applyLateDeliveryPenalty()
    {
        $now = Carbon::now('Asia/Manila');

        // Count late deliveries in the last 7 days, but ignore trips with approved incident reports
        $lateCount = TripTicket::where('user_id', $this->id)
            ->where('status', 'delivered')
            ->where('delay_minutes', '>', 15)
            ->where('delivered_at', '>=', $now->subDays(7))
            ->whereDoesntHave('incidentReport', function ($query) {
                $query->where('status', 'approved');
            })
            ->count();

        // Apply punishment based on the number of late deliveries
        if ($lateCount >= 3 && !$this->restricted_until) {
            $this->restricted_until = Carbon::now('Asia/Manila')->addDays(3); // Restrict for 3 days
        }

        if ($lateCount >= 5) {
            $this->restricted_until = Carbon::now('Asia/Manila')->addDays(7); // Restrict for 1 week
        }

        $this->save();
    }
}
