<?php

namespace App\Services;

use App\Jobs\LeaveJobs\SendLeaveRequestIncomingEmailJob;
use App\Jobs\OvertimeJobs\SendOvertimeRequestAcceptedEmailJob;
use App\Jobs\OvertimeJobs\SendOvertimeRequestRejectedEmailJob;
use App\Models\Employee;
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
                if(!$employee->is_supervisor){
                    $officer_role = Role::findByName('employee');
                    $supervisor_id = $overtime->employee->department->manager->id;
                    $processing_officers = Employee::where('id', $supervisor_id)->get();
                }
                else {
                    $officer_role = Role::findByName('sg');
                    $processing_officers = Employee::role('sg')->get();
                }
                $overtime->processing_officer_role = $officer_role->id;
                break;
            case ('employee'):
                $role_sg = Role::findByName('sg');
                $overtime->processing_officer_role = $role_sg->id;
                $processing_officers = Employee::role('sg')->get();
                if(auth()->user()->hasRole('sg')) {
                    $this->acceptLeave($overtime);
                    $processing_officers = NULL;
                    break;
                }
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

}
