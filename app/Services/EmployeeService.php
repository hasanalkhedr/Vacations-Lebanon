<?php

namespace App\Services;

use App\Models\Employee;

class EmployeeService
{
    public function getAppropriateEmployees() {
        $loggedInUser = auth()->user();
        $loggedInUserRoleName = $loggedInUser->roles()->first()->name;
        if($loggedInUserRoleName == 'human_resource' || $loggedInUserRoleName == 'sg') {
            return Employee::whereNot('id', auth()->id())->get();
        }
        else {
            return Employee::whereNot('id', auth()->id())->where('department_id', $loggedInUser->department_id)->get();
        }
    }

}
