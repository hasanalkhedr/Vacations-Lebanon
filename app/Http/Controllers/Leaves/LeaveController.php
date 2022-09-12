<?php

namespace App\Http\Controllers\Leaves;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequests\StoreLeaveRequest;
use App\Jobs\SendLeaveRequestIncomingEmailJob;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use Spatie\Permission\Models\Role;
use App\Services\LeaveService;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    const PENDING_STATUS = 0;


    public function create() {
        $employee = auth()->user();
        $leave_types = LeaveType::all();
        $today = now();
        if($employee->roles()->first()->name == 'human_resource') {
            $substitutes = Employee::role('human_resource')->get()->except($employee->id);
        }
        elseif ($employee->roles()->first()->name == 'sg'){
            $substitutes = Employee::role('sg')->get()->except($employee->id);
        }
        else{
            $substitutes = Employee::where('department_id', $employee->department_id)->get()->except($employee->id);
        }
        return view('leaves.create',[
            'employee' => $employee,
            'leave_types' => $leave_types,
            'today' => $today,
            'department' => $employee->department,
            'substitutes' => $substitutes,

        ]);
    }

    public function store(StoreLeaveRequest $request) {
        $validated = $request->validated();
        $leave = Leave::create([
            'employee_id' => auth()->user()->id,
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
        $role = Role::findByName('human_resource');
        $leave->processing_officer_role = $role->id;
        $leave->save();

        $processing_officers = Employee::role('human_resource')->get();
        $leave_service = new LeaveService();
        $leave_service->sendEmailToInvolvedEmployees($leave, $processing_officers);
        return redirect()->route('employees.home');
    }


    public function index() {
        $employee_role = auth()->user()->roles()->first()->id;
        $leaves = Leave::where('processing_officer_role', $employee_role)->where('leave_status', self::PENDING_STATUS)->get();
        return view('leaves.index', ['leaves' => $leaves]);
    }

    public function show(Leave $leave) {
        {
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

//    public function downloadAttachment(Leave $leave) {
//        $leave_service = new LeaveService();
//        $leave_service->downloadAttachment($leave);
//        return redirect()->route('leaves.index');
//    }
}
