<?php

namespace App\Services;

use App\Jobs\LeaveJobs\SendLeaveRequestIncomingEmailJob;
use App\Jobs\OvertimeJobs\SendOvertimeRequestAcceptedEmailJob;
use App\Jobs\OvertimeJobs\SendOvertimeRequestRejectedEmailJob;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Overtime;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
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
    //    $this->sendEmailToInvolvedEmployees($overtime, $processing_officers);
    }

    public function rejectLeaveRequest($request, $overtime) {
        $overtime->overtime_status = self::REJECTED_STATUS;
        if($request['cancellation_reason']) {
            $overtime->cancellation_reason = $request['cancellation_reason'];
        }
        $overtime->rejected_by = auth()->user()->id;
        $overtime->save();
    //    $this->sendEmailToInvolvedEmployees($overtime);
    }

    public function acceptLeave($overtime) {
        $overtime->overtime_status = self::ACCEPTED_STATUS;
        $overtime->save();
    }

    public function fetchOvertimes($employee_id, $from_date, $to_date) {
        $overtimes = Overtime::where('employee_id', $employee_id)->where('overtime_status', self::ACCEPTED_STATUS)->whereDate('date', '>=', $from_date)->whereDate('date', '<=', $to_date)->get();
        $total_time = $this-> getTotalOvertime($overtimes);
        $data['overtimes'] = $overtimes;
        $data['total_time'] = $total_time;
        return $data;
    }

    public function getTotalOvertime($overtimes) {
        $totalMinutes = 0;
        foreach ($overtimes as $overtime) {
            $time = Carbon::createFromTimeString($overtime->hours);
            $start_of_day = Carbon::createFromTimeString($overtime->hours)->startOfDay();
            $minutes = $time->diffInMinutes($start_of_day);
            $totalMinutes += $minutes;
        }
        $hours = floor($totalMinutes / 60);
        $mins = floor($totalMinutes % 60);
        $secs = floor($totalMinutes *60 % 60);
        $total_time = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        return $total_time;
    }
}
