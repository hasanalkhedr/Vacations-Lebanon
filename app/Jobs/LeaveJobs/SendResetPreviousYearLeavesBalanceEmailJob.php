<?php

namespace App\Jobs\LeaveJobs;

use App\Mail\LeaveMails\SendLeaveRequestAcceptedEmail;
use App\Mail\LeaveMails\SendResetPreviousYearLeavesBalanceEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendResetPreviousYearLeavesBalanceEmailJob implements ShouldQueue
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
        $email = new SendResetPreviousYearLeavesBalanceEmail();
        Log::info('email: ' . $this->employee->email);
        Mail::to($this->employee->email)->send($email);
    }
}
