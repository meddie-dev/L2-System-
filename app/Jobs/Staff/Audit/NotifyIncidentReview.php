<?php

namespace App\Jobs\Staff\Audit;

use App\Models\IncidentReport;
use App\Models\User;
use App\Notifications\NewNotification;
use App\Notifications\staff\staffApprovalStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyIncidentReview implements ShouldQueue
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
            $user->notify(new staffApprovalStatus('Incident', $this->incidentReport->reportNumber));
            $user->notify(new NewNotification("Your incident report ({$this->incidentReport->reportNumber}) has been {$this->incidentReport->approval_status}."));
        }

        $admins = User::role('Admin')->first();

        if ($admins) {
            $this->incidentReport->redirected_to = $admins->id;
            $this->incidentReport->notify(new NewNotification("An incident report ({$this->incidentReport->reportNumber}) has been {$this->incidentReport->approval_status}."));
        }
    }
}