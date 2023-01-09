<?php

namespace App\Jobs\LeaveJobs;

use App\Mail\LeaveMails\SendLeaveRequestAcceptedEmail;
use App\Mail\LeaveMails\SendLeaveRequestAcceptedEmailReplacement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendLeaveRequestAcceptedEmailReplacementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $employee;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($employee)
    {
        $this->employee= $employee;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('email: ' . $this->employee->email);
        $email = new SendLeaveRequestAcceptedEmailReplacement();
        Mail::to($this->employee->email)->send($email);
    }
}
