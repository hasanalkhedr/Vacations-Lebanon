<?php

namespace App\Services;

use App\Helpers\Helper;
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
use App\Models\LeaveType;
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
                else {
                    $role = Role::findByName('sg');
                    $leave->processing_officer_role = $role->id;
                    $processing_officers = Employee::role('sg')->get();
                }
                break;
            case ('employee'):
                if (auth()->user()->hasRole('sg')) {
                    $role_sg = Role::findByName('sg');
                    $leave->processing_officer_role = $role_sg->id;
                    $this->acceptLeave($leave);
                    $processing_officers = NULL;
                    break;
                }
                $role = Role::findByName('human_resource');
                $leave->processing_officer_role = $role->id;
                $processing_officers = Employee::role('human_resource')->get();
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
        $leave->rejected_by = auth()->user()->id;
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
            $nb_of_days_off = $this->findNbofDaysOff($leave);
            if($leave->mix_of_leaves) {
                $nb_of_days_off_confessionnels = $this->findNbofDaysOffConfessionnels($leave);
                $employee->confessionnels = $employee->confessionnels - $nb_of_days_off_confessionnels;
            }
            $employee->nb_of_days = $employee->nb_of_days - $nb_of_days_off;
        }

        $employee->save();
    }

    public function getDisabledDates($employee)
    {
        $leaves = Leave::where('employee_id', $employee->id)->get();
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

    public function getConfessionnelDates()
    {
        $confessionnels = Confessionnel::all();
        $confessionnel_dates = [];
        foreach ($confessionnels as $confessionnel) {
            $confessionnel_dates[] = $confessionnel->date;
        }
        return $confessionnel_dates;
    }

    public function isConfessionnel($date) {
        $confessionnels = $this->getConfessionnelDates();
        return (in_array($date, $confessionnels));
    }

    public function getConfessionnels()
    {
        $confessionnels = Confessionnel::whereNotIn('date', Leave::where('employee_id', auth()->user()->id)->where('use_confessionnels', true)->get('from'))->get();
        $mix_leaves = Leave::where('employee_id', auth()->user()->id)->where('mix_of_leaves', true)->get();
        $mix_confessionnels = [];
        foreach ($mix_leaves as $mix_leave) {
            $period = CarbonPeriod::create($mix_leave->from, $mix_leave->to);
            // Iterate over the period
            foreach ($period as $date) {
                if (!in_array($date->toDateString(), $mix_confessionnels))
                    $mix_confessionnels[] = $date->toDateString();
            }
        }
        $confessionnel_dates = [];
        foreach ($confessionnels as $confessionnel) {
            if(!in_array($confessionnel->date, $mix_confessionnels))
                $confessionnel_dates[] = $confessionnel->date;
        }
        return $confessionnel_dates;
    }

    public function findNbofDaysOff($leave) {
        $helper = new Helper();
        $period = CarbonPeriod::create($leave->from, $leave->to);
        $nb_of_days_off = 0;
        $disabled_dates = unserialize($leave->disabled_dates);
        foreach ($period as $date) {
            $date = $date->toDateString();
            if (!$helper->isWeekend($date) && !in_array($date, $disabled_dates) && !$helper->isHoliday($date) && !$this->isConfessionnel($date)) {
                $nb_of_days_off = $nb_of_days_off + 1;
            }
        }
        $leave_duration_name = $leave->leave_duration->name;
        if ($leave_duration_name == "Half Day AM" || $leave_duration_name == "Half Day PM") {
            $nb_of_days_off = $nb_of_days_off / 2;
        }
        return $nb_of_days_off;
    }

    public function findNbofDaysOffConfessionnels($leave) {
        $helper = new Helper();
        $period = CarbonPeriod::create($leave->from, $leave->to);
        $nb_of_days_off_confessionnels = 0;
        $disabled_dates = unserialize($leave->disabled_dates);
        foreach ($period as $date) {
            $date = $date->toDateString();
            if (!$helper->isWeekend($date) && !in_array($date, $disabled_dates) && !$helper->isHoliday($date) && $this->isConfessionnel($date)) {
                $nb_of_days_off_confessionnels = $nb_of_days_off_confessionnels + 1;
            }
        }
        $leave_duration_name = $leave->leave_duration->name;
        if ($leave_duration_name == "Half Day AM" || $leave_duration_name == "Half Day PM") {
            $nb_of_days_off_confessionnels = $nb_of_days_off_confessionnels / 2;
        }
        return $nb_of_days_off_confessionnels;
    }

    public function fetchLeaves($employee_id, $from_date, $to_date) {
        $leaves = Leave::where('employee_id', $employee_id)->where('leave_status', self::ACCEPTED_STATUS)
                        ->where(function($query) use($from_date, $to_date) {
                            $query->where(function($query) use($from_date, $to_date) {
                                        $query->whereDate('from', '>=', $from_date)->whereDate('from', '<=', $to_date);})
                                    ->orWhere(function($query) use($from_date, $to_date) {
                                        $query->whereDate('to', '>=', $from_date)->whereDate('to', '<=', $to_date);});
                        })->paginate(20);
        $leave_types = LeaveType::all();
        foreach ($leave_types as $leave_type) {
            $data[$leave_type->name] = $this->filterLeaves($leaves, $leave_type);
        }
        $data['leaves'] = $leaves;
        return $data;
    }

    public function filterLeaves($leaves, $leave_type) {
        ${"$leave_type->name"}  = $leaves->filter(function($value, $key) use ($leave_type) {
            if($value['leave_type_id'] == LeaveType::where('name', $leave_type->name)->first()->id)
                return true;
        });
        return ${"$leave_type->name"};
    }
}
