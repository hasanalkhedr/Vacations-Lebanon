<?php

namespace App\Jobs;

use App\Mail\SendLeaveRequestEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendLeaveRequestEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $employees_email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($employees_email)
    {
        $this->employees_email = $employees_email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new SendLeaveRequestEmail();
        Mail::to($this->employees_email)->send($email);
    }
}
