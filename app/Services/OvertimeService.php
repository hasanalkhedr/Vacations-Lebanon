<?php

namespace App\Services;

use App\Jobs\LeaveJobs\SendLeaveRequestIncomingEmailJob;
use App\Jobs\OvertimeJobs\SendOvertimeRequestAcceptedEmailJob;
use App\Jobs\OvertimeJobs\SendOvertimeRequestRejectedEmailJob;
use App\Models\Employee;
use App\Models\Holiday;
use Carbon\CarbonPeriod;
use Spatie\Permission\Models\Role;

class OvertimeService
{
    const ACCEPTED_STATUS = 1;
    const REJECTED_STATUS = 2;

    public function sendEmailToInvolvedEmployees($overtime, $processing_officers = NULL) {
        if($overtime->overtime_status == self::ACCEPTED_STATUS){
            $employee = Employee::where('id', $overtime->employee_id)->first();
            dispatch(new SendOvertimeRequestAcceptedEmailJob($employee));
        }
        elseif($overtime->overtime_status == self::REJECTED_STATUS){
            $employee = Employee::where('id', $overtime->employee_id)->first();
            dispatch(new SendOvertimeRequestRejectedEmailJob($employee));
        }
        else {
            foreach ($processing_officers as $processing_officer) {
                dispatch(new SendOvertimeRequestAcceptedEmailJob($processing_officer));
            }
        }
    }

    public function checkProcessingOfficerandElevateRequest($overtime) {
        $employee = $overtime->employee;
        $processing_officer_role = $overtime->processing_officer_role;
        $role = Role::findById($processing_officer_role);
        switch ($role->name){
            case ('human_resource'):
                if(auth()->user()->hasRole('sg')) {
                    $role_sg = Role::findByName('sg');
                    $overtime->processing_officer_role = $role_sg->id;
                    $this->acceptLeave($overtime);
                    $processing_officers = NULL;
                    break;
                }
                else {
                    $role = Role::findByName('sg');
                    $overtime->processing_officer_role = $role->id;
                    $processing_officers = Employee::role('sg')->get();
                }
                break;
            case ('employee'):
                if(auth()->user()->hasRole('sg')) {
                    $role_sg = Role::findByName('sg');
                    $overtime->processing_officer_role = $role_sg->id;
                    $this->acceptLeave($overtime);
                    $processing_officers = NULL;
                    break;
                }
                $role = Role::findByName('human_resource');
                $overtime->processing_officer_role = $role->id;
                $processing_officers = Employee::role('human_resource')->get();
                break;
            case ('sg'):
                $this->acceptLeave($overtime);
                $processing_officers = NULL;
                break;
        }
        $overtime->save();
//        $this->sendEmailToInvolvedEmployees($overtime, $processing_officers);
    }

    public function rejectLeaveRequest($request, $overtime) {
        $overtime->overtime_status = self::REJECTED_STATUS;
        if($request['cancellation_reason']) {
            $overtime->cancellation_reason = $request['cancellation_reason'];
        }
        $overtime->save();
//        $this->sendEmailToInvolvedEmployees($overtime);
    }

    public function acceptLeave($overtime) {
        $overtime->overtime_status = self::ACCEPTED_STATUS;
        $overtime->save();
    }

    public function getHolidays()
    {
        $holidays = Holiday::all();
        $holiday_dates = [];
        foreach ($holidays as $holiday) {
            $period = CarbonPeriod::create($holiday->from, $holiday->to);
            // Iterate over the period
            foreach ($period as $date) {
                if (!in_array($date->toDateString(), $holiday_dates))
                    $holiday_dates[] = $date->toDateString();
            }
        }
        return $holiday_dates;
    }
}
