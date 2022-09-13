<?php

namespace App\Services;

use App\Jobs\SendLeaveRequestAcceptedEmailJob;
use App\Jobs\SendLeaveRequestIncomingEmailJob;
use App\Jobs\SendLeaveRequestRejectedEmailJob;
use App\Models\Employee;
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
        $processing_officer_role = $leave->processing_officer_role;
        $role = Role::findById($processing_officer_role);
        switch ($role->name){
            case ('human_resource'):
                $role_supervisor = Role::findByName('supervisor');
                $leave->processing_officer_role = $role_supervisor->id;
                $processing_officers = Employee::role('supervisor')->get();
                break;
            case ('supervisor'):
                $role_sg = Role::findByName('sg');
                $leave->processing_officer_role = $role_sg->id;
                $processing_officers = Employee::role('sg')->get();
                break;
            case ('sg'):
                $leave->leave_status = self::ACCEPTED_STATUS;
                break;
        }
        $leave->save();
        $this->sendEmailToInvolvedEmployees($leave);
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

}
