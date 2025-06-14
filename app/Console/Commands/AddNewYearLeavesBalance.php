<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\LeaveConfig;
use App\Services\LeaveService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AddNewYearLeavesBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave-balance:new-year';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add annual leave balance (30 days) for each employee (only ones can submit requests)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $startDate = Carbon::create(null, LeaveConfig::find('start_month')->value, LeaveConfig::find('start_day')->value);
        if (now()->get('year') == LeaveConfig::find('year')->value + 1) {
            if (!now()->isBefore($startDate)) {
                $leaveService = new LeaveService();
                $leaveService->newYearLeaves();
                $yearConfig = LeaveConfig::find('year');
                $yearConfig->value = now()->get('year');
                $yearConfig->save();
                $this->info('Leave balance for year '.now()->get("year").' added successfully');
                return Command::SUCCESS;
            } else {
                $this->error('current date is before specified date to add new year leaves balance');
                return Command::FAILURE;
            }
        } else {
            $this->error('You already add leave balance for this year');
            return Command::FAILURE;
        }
    }
}

