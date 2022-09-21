<?php

namespace App\Http\Controllers\Leaves;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequests\StoreLeaveRequest;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveDuration;
use App\Models\LeaveType;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Spatie\Permission\Models\Role;
use App\Services\LeaveService;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    const PENDING_STATUS = 0;


    public function create() {
        $employee = auth()->user();
        $leave_durations = LeaveDuration::all();
        $leave_types = LeaveType::all();
        $today = now();
        if($employee->roles()->first()->name == 'human_resource') {
            $substitutes = Employee::role('human_resource')->get()->except($employee->id);
        }
        elseif ($employee->roles()->first()->name == 'sg'){
            $substitutes = Employee::role('sg')->get()->except($employee->id);
        }
        else{
            $substitutes = Employee::where('department_id', $employee->department_id)->role($employee->roles()->first()->name)->get()->except($employee->id);
        }
        $leave_service = new LeaveService();
        $disabled_dates = $leave_service->getDisabledDates($employee);
        return view('leaves.create',[
            'employee' => $employee,
            'leave_durations' => $leave_durations,
            'leave_types' => $leave_types,
            'today' => $today,
            'department' => $employee->department,
            'substitutes' => $substitutes,
            'disabled_dates' => $disabled_dates,
        ]);
    }

    public function store(StoreLeaveRequest $request) {
        $validated = $request->validated();
        $leave_service = new LeaveService();
        $disabled_dates = $leave_service->getDisabledDates(auth()->user());
        $serializedArr = serialize($disabled_dates);
        $leave = Leave::create([
            'employee_id' => auth()->user()->id,
            'leave_duration_id' => $validated['leave_duration_id'],
            'from' => $validated['from'],
            'to' => $validated['to'],
            'travelling' => $validated['travelling'],
            'leave_type_id' => $validated['leave_type_id'],
        ]);
        if($request->hasFile('attachment_path')) {
            $leave['attachment_path'] = $request->file('attachment_path')->store('attachments', 'public');
        }
        $leave->date_of_submission = now()->format('Y/m/d');
        if($request['substitute_employee_id']) {
            $leave->substitute_employee_id = $request['substitute_employee_id'];
        }
        $leave->disabled_dates = $serializedArr;
        $leave_employee_role = $leave->employee->roles()->first()->name;
        if($leave_employee_role == "employee" || $leave_employee_role == "supervisor") {
            $role = Role::findByName('human_resource');
            $processing_officers = Employee::role('human_resource')->get();
            $leave->processing_officer_role = $role->id;
            $leave->save();
        }
        elseif($leave_employee_role == "human_resource") {
            $role = Role::findByName('sg');
            $processing_officers = Employee::role('sg')->get();
            $leave->processing_officer_role = $role->id;
            $leave->save();
        }
        else {
            $role = Role::findByName('sg');
            $leave->processing_officer_role = $role->id;
            $leave->save();
            $processing_officers = NULL;
            $leave_service->acceptLeave($leave);
        }
        $leave_service->sendEmailToInvolvedEmployees($leave, $processing_officers);
        return redirect()->route('leaves.submitted');
    }


    public function index() {
        $employee=auth()->user();
        $leave_durations = LeaveDuration::all();
        $leave_types = LeaveType::all();
        $today = now();
        $employee_role = $employee->roles()->first()->id;
        if($employee->roles()->first()->name == "supervisor"){
            $leaves = Leave::where('processing_officer_role', $employee_role)->where('leave_status', self::PENDING_STATUS)->whereIn('employee_id', $employee->department->employees->pluck('id')->toarray())->search(request(['search']))->paginate(10);
        }
        else {
            $leaves = Leave::where('processing_officer_role', $employee_role)->where('leave_status', self::PENDING_STATUS)->search(request(['search']))->paginate(10);
        }

        if($employee->roles()->first()->name == 'human_resource') {
            $substitutes = Employee::role('human_resource')->get()->except($employee->id);
        }
        elseif ($employee->roles()->first()->name == 'sg'){
            $substitutes = Employee::role('sg')->get()->except($employee->id);
        }
        else{
            $substitutes = Employee::where('department_id', $employee->department_id)->get()->except($employee->id);
        }
        return view('leaves.index', [
            'leaves' => $leaves,
            'employee' => $employee,
            'leave_durations' => $leave_durations,
            'leave_types' => $leave_types,
            'today' => $today,
            'department' => $employee->department,
            'substitutes' => $substitutes
        ]);
    }

    public function show(Leave $leave) {
        {
            $loggedInRole = auth()->user()->roles()->first();
            if($loggedInRole->name == "employee"){
                if($leave->employee_id != auth()->user()->id) {
                    return back();
                }
            }
            elseif ($loggedInRole->id != $leave->processing_officer_role) {
                return back();
            }

            return view('leaves.show', [
                'leave' => $leave
            ]);
        }
    }

    public function accept(Leave $leave) {
        $leave_service = new LeaveService();
        $leave_service->checkProcessingOfficerandElevateRequest($leave);
        return redirect()->route('leaves.index');
    }

    public function reject(Request $request, Leave $leave) {
        $leave_service = new LeaveService();
        $leave_service->rejectLeaveRequest($request, $leave);
        return redirect()->route('leaves.index');
    }

    public function submitted() {
        $leaves = auth()->user()->leaves;
        return view('leaves.submitted', [
            'leaves' => $leaves
        ]);
    }

//    public function downloadAttachment(Leave $leave) {
//        $leave_service = new LeaveService();
//        $leave_service->downloadAttachment($leave);
//        return redirect()->route('leaves.index');
//    }



    public function destroy(Leave $leave) {
        $leave->delete();
        return redirect()->route('leaves.submitted');
    }
}
