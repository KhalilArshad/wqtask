<?php

namespace App\Listeners;

use App\Events\RecordNotFoundEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecordNotFoundMail;

class SendEmailWhenRecordNotFound implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RecordNotFoundEvent $event): void
    {
        // Send email with a delay of 1.5 minutes (90 seconds)
        Mail::to('khalilaabad@gmail.com')->later(now()->addSeconds(90), new RecordNotFoundMail());
    }
}

