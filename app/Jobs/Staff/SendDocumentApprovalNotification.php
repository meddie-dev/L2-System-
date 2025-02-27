<?php

namespace App\Jobs\Staff;

use App\Models\Modules\Document;
use App\Models\User;
use App\Notifications\admin\adminApprovalRequest;
use App\Notifications\NewNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDocumentApprovalNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function handle()
    {
        $admin = User::role('Admin')->first();
        if ($admin) {
            $this->document->redirected_to = $admin->id;
            $this->document->save();

            $admin->notify(new adminApprovalRequest('Document', $this->document));
            $admin->notify(new NewNotification("Reviewed Document from {$this->document->user->firstName} {$this->document->user->lastName} with Document Number: ({$this->document->documentNumber}). Waiting for your approval."));
        }
    }
}