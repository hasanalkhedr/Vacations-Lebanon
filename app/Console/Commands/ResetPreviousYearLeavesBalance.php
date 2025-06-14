<?php

namespace App\Console\Commands;

use App\Jobs\LeaveJobs\SendResetPreviousYearLeavesBalanceEmailJob;
use App\Models\Employee;
use App\Models\LeaveConfig;
use App\Services\LeaveService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
class ResetPreviousYearLeavesBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave-balance:prev-year';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reset previous year leaves balance to zero';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $hr = Employee::role('human_resource')->get();

        $expireDate = Carbon::create(null, LeaveConfig::find('expire_month')->value, LeaveConfig::find('expire_day')->value);
        if (
            now()->get('year') == LeaveConfig::find('year')->value
            && Employee::where('can_submit_requests', true)->sum('prev_leaves') > 0
        ) {
            if (!now()->isBefore($expireDate)) {
                // $leaveService = new LeaveService();
                // $leaveService->expirePrevLeaves();
                // $this->info('Leave balance for previoys year resetted to zero successfully');
                foreach ($hr as $emp) {
                    dispatch(new SendResetPreviousYearLeavesBalanceEmailJob($emp));
                    $this->info('email sent to '. $emp->email);
                }
                return Command::SUCCESS;
            } else {
                $this->error('current date is before specified date to reset previous year leaves balance');
                return Command::FAILURE;
            }
        } else {
            $this->error('You already reset previous year leave balance');
            return Command::FAILURE;
        }
    }
}
