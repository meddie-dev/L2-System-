<?php

namespace App\Jobs\Vendor;

use App\Models\Modules\Document;
use App\Models\User;
use App\Notifications\staff\staffApprovalRequest;
use App\Notifications\NewNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDocumentNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $document;
    protected $user;

    public function __construct(Document $document, User $user)
    {
        $this->document = $document;
        $this->user = $user;
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
            $this->document->assigned_to = $staff->id;
            $this->document->save();

            $staff->notify(new staffApprovalRequest('Document', $this->document));
            $staff->notify(new NewNotification("Document Uploaded by {$this->user->firstName} {$this->user->lastName} with Document Number: ({$this->document->documentNumber}). Waiting for your approval."));
        }
    }
}
