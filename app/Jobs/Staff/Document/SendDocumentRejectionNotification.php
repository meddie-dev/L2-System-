<?php

namespace App\Jobs\Staff\Document;

use App\Models\Modules\Document;
use App\Models\User;
use App\Notifications\NewNotification;
use App\Notifications\staff\staffApprovalStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDocumentRejectionNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function handle()
    {
        $user = User::find($this->document->user_id);
        if ($user) {
            $user->notify(new staffApprovalStatus('Document', $this->document));
            $user->notify(new NewNotification("Your document ({$this->document->documentNumber}) has been rejected. Please review and resubmit."));
        }
    }
}