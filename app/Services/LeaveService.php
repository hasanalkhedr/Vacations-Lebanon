<?php

namespace App\Services;

use App\Jobs\SendLeaveRequestAcceptedEmailJob;
use App\Jobs\SendLeaveRequestIncomingEmailJob;
use App\Jobs\SendLeaveRequestRejectedEmailJob;
use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class LeaveService
{
    const ACCEPTED_STATUS = 1;
    const REJECTED_STATUS = 2;

    public function sendEmailToInvolvedEmployees($leave, $processing_officers = NULL) {
        if($leave->leave_status == self::ACCEPTED_STATUS){
            $employee = Employee::where('id', $leave->employee_id)->first();
            dispatch(new SendLeaveRequestAcceptedEmailJob($employee));
        }
        elseif($leave->leave_status == self::REJECTED_STATUS){
            $employee = Employee::where('id', $leave->employee_id)->first();
            dispatch(new SendLeaveRequestRejectedEmailJob($employee));
        }
        else {
            foreach ($processing_officers as $processing_officer) {
                dispatch(new SendLeaveRequestIncomingEmailJob($processing_officer));
            }
        }
    }

    public function checkProcessingOfficerandElevateRequest($leave) {
        $employee = $leave->employee;
        $processing_officer_role = $leave->processing_officer_role;
        $role = Role::findById($processing_officer_role);
        switch ($role->name){
            case ('human_resource'):
                if($employee->roles()->first()->name == "employee"){
                    $officer_role = Role::findByName('supervisor');
                    $supervisor_id = $leave->employee->department->manager->id;
                    $processing_officers = Employee::where('id', $supervisor_id)->get();
                }
                else {
                    $officer_role = Role::findByName('sg');
                    $processing_officers = Employee::role('sg')->get();
                }
                $leave->processing_officer_role = $officer_role->id;
                break;
            case ('supervisor'):
                $role_sg = Role::findByName('sg');
                $leave->processing_officer_role = $role_sg->id;
                $processing_officers = Employee::role('sg')->get();
                break;
            case ('sg'):
                $this->acceptLeave($leave);
                $processing_officers = NULL;
                break;
        }
        $leave->save();
        $this->sendEmailToInvolvedEmployees($leave, $processing_officers);
    }

    public function rejectLeaveRequest($request, $leave) {
        $leave->leave_status = self::REJECTED_STATUS;
        if($request['cancellation_reason']) {
            $leave->cancellation_reason = $request['cancellation_reason'];
        }
        $leave->save();
        $this->sendEmailToInvolvedEmployees($leave);
    }

//    public function downloadAttachment($leave) {
//        Storage::download('/public/' . $leave->attachment_path);
//    }


    public function acceptLeave($leave) {
        $this->updateNbOfDaysOff($leave);
        $leave->leave_status = self::ACCEPTED_STATUS;
        $leave->save();
    }

    public function updateNbOfDaysOff($leave) {
        $employee = $leave->employee;
        $period = CarbonPeriod::create($leave->from, $leave->to);
        $nb_of_days_off = 0;
        $disabled_dates = unserialize($leave->disabled_dates);
        Log::info(gettype($disabled_dates));
        foreach ($period as $date) {
            Log::info('--------------------------');
            $date = $date->toDateString();
            Log::info($date);

            if(!$this->isWeekend($date)){
                Log::info("not a weekend");
            }
            if(!in_array($date, $disabled_dates) ){
                Log::info("Not disabled date");
            }
            if(!$this->isWeekend($date) && !in_array($date, $disabled_dates) ){
                $nb_of_days_off = $nb_of_days_off + 1;
                Log::info("nb of days off: " . $nb_of_days_off);
            }
        }
        $leave_duration_name = $leave->leave_duration->name;
        if ($leave_duration_name == "Half Day AM" || $leave_duration_name == "Half Day PM") {
            $nb_of_days_off = $nb_of_days_off / 2;
        }
        $employee->nb_of_days = $employee->nb_of_days - $nb_of_days_off;
        $employee->save();
    }

    public function isWeekend($date) {
        Log::alert($date . date('N', strtotime($date)));
        return (date('N', strtotime($date)) == 7 || date('N', strtotime($date)) == 6);
    }

    public function getDisabledDates($employee)
    {
        $leaves = Leave::where('employee_id', $employee->id)->whereNot('to', '<', now())->get();
        $disabled_dates = [];
        foreach ($leaves as $leave) {
        $period = CarbonPeriod::create($leave->from, $leave->to);
            // Iterate over the period
            foreach ($period as $date) {
                if(!in_array($date->toDateString(), $disabled_dates))
                    $disabled_dates[] = $date->toDateString();
            }
        }
        return $disabled_dates;
    }
}
