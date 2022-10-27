<?php

namespace App\Services;

use App\Jobs\LeaveJobs\SendLeaveRequestAcceptedEmailJob;
use App\Jobs\LeaveJobs\SendLeaveRequestAcceptedEmailReplacementJob;
use App\Jobs\LeaveJobs\SendLeaveRequestCanceledEmailJob;
use App\Jobs\LeaveJobs\SendLeaveRequestIncomingEmailJob;
use App\Jobs\LeaveJobs\SendLeaveRequestIncomingEmailReplacementJob;
use App\Jobs\LeaveJobs\SendLeaveRequestRejectedEmailJob;
use App\Jobs\LeaveJobs\SendLeaveRequestRejectedEmailProcessingOfficersJob;
use App\Jobs\LeaveJobs\SendLeaveRequestRejectedEmailReplacementJob;
use App\Mail\LeaveMails\SendLeaveRequestRejectedEmailProcessingOfficers;
use App\Models\Confessionnel;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Leave;
use Carbon\CarbonPeriod;
use Spatie\Permission\Models\Role;

class LeaveService
{
    const ACCEPTED_STATUS = 1;
    const REJECTED_STATUS = 2;

    public function sendEmailToInvolvedEmployees($leave, $processing_officers = NULL, $substitute_employee = NULL, $delete = false)
    {
        if ($delete) {
            if($substitute_employee) {
                dispatch(new SendLeaveRequestCanceledEmailJob($substitute_employee));
            }
            foreach ($processing_officers as $processing_officer) {
                dispatch(new SendLeaveRequestCanceledEmailJob($processing_officer));
            }
        }
        else {
            if ($leave->leave_status == self::ACCEPTED_STATUS) {
                $employee = Employee::where('id', $leave->employee_id)->first();
                dispatch(new SendLeaveRequestAcceptedEmailJob($employee));
                dispatch(new SendLeaveRequestAcceptedEmailReplacementJob($substitute_employee));
            } elseif ($leave->leave_status == self::REJECTED_STATUS) {
                $employee = Employee::where('id', $leave->employee_id)->first();
                dispatch(new SendLeaveRequestRejectedEmailJob($employee));
                dispatch(new SendLeaveRequestRejectedEmailReplacementJob($substitute_employee));
            } else {
                foreach ($processing_officers as $processing_officer) {
                    dispatch(new SendLeaveRequestIncomingEmailJob($processing_officer));
                }
                if ($substitute_employee) {
                    dispatch(new SendLeaveRequestIncomingEmailReplacementJob($substitute_employee));
                }
            }
        }
    }

    public function checkProcessingOfficerandElevateRequest($leave)
    {
        $employee = $leave->employee;
        $processing_officer_role = $leave->processing_officer_role;
        $role = Role::findById($processing_officer_role);
        switch ($role->name) {
            case ('human_resource'):
                if (auth()->user()->hasRole('sg')) {
                    $role_sg = Role::findByName('sg');
                    $leave->processing_officer_role = $role_sg->id;
                    $this->acceptLeave($leave);
                    $processing_officers = NULL;
                    break;
                }
                if (!$employee->is_supervisor) {
                    $officer_role = Role::findByName('employee');
                    $supervisor_id = $leave->employee->department->manager->id;
                    $processing_officers = Employee::where('id', $supervisor_id)->get();
                } else {
                    $officer_role = Role::findByName('sg');
                    $processing_officers = Employee::role('sg')->get();
                }
                $leave->processing_officer_role = $officer_role->id;
                break;
            case ('employee'):
                $role_sg = Role::findByName('sg');
                $leave->processing_officer_role = $role_sg->id;
                $processing_officers = Employee::role('sg')->get();
                if (auth()->user()->hasRole('sg')) {
                    $this->acceptLeave($leave);
                    $processing_officers = NULL;
                    break;
                }
                break;
            case ('sg'):
                $this->acceptLeave($leave);
                $processing_officers = NULL;
                break;
        }
        $leave->save();
        if($leave->leave_status == self::ACCEPTED_STATUS){
            $this->sendEmailToInvolvedEmployees($leave, $processing_officers, $leave->substitute_employee);
        }
        else{
            $this->sendEmailToInvolvedEmployees($leave, $processing_officers);
        }

    }

    public function rejectLeaveRequest($request, $leave)
    {
        $leave->leave_status = self::REJECTED_STATUS;
        if ($request['cancellation_reason']) {
            $leave->cancellation_reason = $request['cancellation_reason'];
        }
        $leave->save();
        $this->sendEmailToInvolvedEmployees($leave, NULL, $leave->substitute_employee);
    }

    public function acceptLeave($leave)
    {
        $this->updateNbOfDaysOff($leave);
        $leave->leave_status = self::ACCEPTED_STATUS;
        $leave->save();
    }

    public function updateNbOfDaysOff($leave)
    {
        $employee = $leave->employee;
        if ($leave->use_confessionnels) {
            $employee->confessionnels = $employee->confessionnels - 1;
        }
        else{
            $period = CarbonPeriod::create($leave->from, $leave->to);
            $nb_of_days_off = 0;
            $disabled_dates = unserialize($leave->disabled_dates);
            foreach ($period as $date) {
                $date = $date->toDateString();
                if (!$this->isWeekend($date) && !in_array($date, $disabled_dates) && !$this->isHoliday($date)) {
                    $nb_of_days_off = $nb_of_days_off + 1;
                }
            }
            $leave_duration_name = $leave->leave_duration->name;
            if ($leave_duration_name == "Half Day AM" || $leave_duration_name == "Half Day PM") {
                $nb_of_days_off = $nb_of_days_off / 2;
            }
            $employee->nb_of_days = $employee->nb_of_days - $nb_of_days_off;
        }

        $employee->save();
    }

    public function isWeekend($date)
    {
        return (date('N', strtotime($date)) == 7 || date('N', strtotime($date)) == 6);
    }

    public function getDisabledDates($employee)
    {
        $leaves = Leave::where('employee_id', $employee->id)->whereDate('to', '>=', now()->format('Y-m-d'))->get();
        $disabled_dates = [];
        foreach ($leaves as $leave) {
            $period = CarbonPeriod::create($leave->from, $leave->to);
            // Iterate over the period
            foreach ($period as $date) {
                if (!in_array($date->toDateString(), $disabled_dates))
                    $disabled_dates[] = $date->toDateString();
            }
        }

        return $disabled_dates;
    }

    public function getHolidays()
    {
        $holidays = Holiday::whereDate('to', '>=', now()->format('Y-m-d'))->get();
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

    public function isHoliday($date) {
        $holidays = $this->getHolidays();
        return (in_array($date, $holidays));
    }

    public function getConfessionnels()
    {
        $confessionnels = Confessionnel::all();
        $confessionnel_dates = [];
        foreach ($confessionnels as $confessionnel) {
            $confessionnel_dates[] = $confessionnel->date;
        }
        return $confessionnel_dates;
    }
}
