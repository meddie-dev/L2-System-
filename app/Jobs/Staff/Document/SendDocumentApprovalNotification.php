<?php

namespace App\Jobs\Staff\Document;

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
        }

        $creator = User::find($this->document->user_id);
        if ($creator) {
            $creator->notify(new adminApprovalRequest('Document', $this->document));
            $creator->notify(new NewNotification("Your document ({$this->document->documentNumber}) has been reviewed. Please wait for approval."));
        }
    }
}