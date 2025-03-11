<?php

namespace App\Jobs\Staff\Audit;

use App\Models\IncidentReport;
use App\Models\User;
use App\Notifications\admin\adminApprovalStatus;
use App\Notifications\NewNotification;
use App\Notifications\staff\staffApprovalStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyIncidentApproved implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $incidentReport;

    public function __construct(IncidentReport $incidentReport)
    {
        $this->incidentReport = $incidentReport;
    }

    public function handle()
    {
        // Notify the user who submitted the report
        $user = User::find($this->incidentReport->user_id);
        if ($user) {
            $user->notify(new adminApprovalStatus('Incident', $this->incidentReport->reportNumber));
            $user->notify(new NewNotification("Your incident report ({$this->incidentReport->reportNumber}) has been {$this->incidentReport->approval_status}."));
        }
    }
}