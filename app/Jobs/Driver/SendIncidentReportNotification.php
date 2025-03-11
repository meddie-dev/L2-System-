<?php

namespace App\Jobs\Driver;

use App\Models\IncidentReport;
use App\Models\User;
use App\Notifications\NewNotification;
use App\Notifications\staff\staffApprovalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendIncidentReportNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $incidentReport;

    public function __construct(IncidentReport $incidentReport)
    {
        $this->incidentReport = $incidentReport;
    }

    public function handle()
    {
        $activeStaffs = User::role('Staff')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Staff')
                    ->where('last_active_at', '>=', now()->subMinutes(5));
            })
            ->orderBy('last_active_at', 'desc')
            ->limit(1)
            ->get();

        if ($activeStaffs->isEmpty()) {
            $activeStaffs = User::role('Admin')->get();
        }
        foreach ($activeStaffs as $staff) {
            $this->incidentReport->assigned_to = $staff->id;
            $this->incidentReport->save();
            
            $staff->notify(new staffApprovalRequest('Incident', $this->incidentReport));
            $staff->notify(new NewNotification("A new incident report ({$this->incidentReport->reportNumber}) has been submitted."));
        }
    }
}
