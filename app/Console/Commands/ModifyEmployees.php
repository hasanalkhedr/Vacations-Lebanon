<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

class ModifyEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modify:employees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Modify Employees can_submit_requests column and create new Role';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $employees = Employee::all();
            foreach ($employees as $employee) {
                if ($employee->is_supervisor || $employee->hasRole(['sg'])) {
                    $employee->can_submit_requests = false;
                } else {
                    $employee->can_submit_requests = true;
                }
                $employee->save();
            }

            if (!Role::findByName('head')) {
                $role = Role::create(['name' => 'head']);
                $role->display_name = "IFL director";
                $role->save();
            }

            Artisan::call('optimize:clear');
            $this->line('<fg=green>Employees can_submit_leaves modified correctly and role created correctly.');
        } catch (\Exception $e) {
            $this->error('Employees modification failed.');
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
