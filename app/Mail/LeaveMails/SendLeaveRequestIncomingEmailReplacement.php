<?php

namespace App\Mail\LeaveMails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendLeaveRequestIncomingEmailReplacement extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Leave Request Replacement')
            ->view('emails.leaves.incoming-leave-request-replacement');
    }
}
