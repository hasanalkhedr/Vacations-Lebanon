<?php

namespace App\Jobs\OvertimeJobs;

use App\Mail\OvertimeMails\SendOvertimeRequestIncomingEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOvertimeRequestIncomingEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $employee_email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($employee_email)
    {
        $this->employee_email= $employee_email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new SendOvertimeRequestIncomingEmail();
        Mail::to($this->employee_email)->send($email);
    }
}
