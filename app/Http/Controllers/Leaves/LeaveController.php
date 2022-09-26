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
        if($employee->hasRole("sg")) {
            return back();
        }
        $leave_durations = LeaveDuration::all();
        $leave_types = LeaveType::all();
        $today = now();
        if($employee->hasRole("human_resource")) {
            $substitutes = Employee::role('human_resource')->get()->except($employee->id);
        }
        elseif ($employee->hasRole("employee")){
            $substitutes = Employee::where('department_id', $employee->department_id)->role($employee->roles()->first()->name)->get()->except($employee->id);
        }
        else{
            $substitutes = Employee::where('department_id', $employee->department_id)->get()->except($employee->id);
        }
        $leave_service = new LeaveService();
        $disabled_dates = $leave_service->getDisabledDates($employee);
        $holiday_dates = $leave_service->getHolidays();
        return view('leaves.create',[
            'employee' => $employee,
            'leave_durations' => $leave_durations,
            'leave_types' => $leave_types,
            'today' => $today,
            'department' => $employee->department,
            'substitutes' => $substitutes,
            'disabled_dates' => $disabled_dates,
            'holiday_dates' => $holiday_dates
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
        if($request->confessionnels) {
            $leave->use_confessionnels = true;
        }
        if($request->hasFile('attachment_path')) {
            $leave['attachment_path'] = $request->file('attachment_path')->store('attachments', 'public');
        }
        $leave->date_of_submission = now()->format('Y/m/d');
        if($request['substitute_employee_id']) {
            $leave->substitute_employee_id = $request['substitute_employee_id'];
        }
        $leave->disabled_dates = $serializedArr;
        if($leave->employee->hasRole('sg'))  {
            $role = Role::findByName('sg');
            $leave->processing_officer_role = $role->id;
            $leave->save();
            $processing_officers = NULL;
            $leave_service->acceptLeave($leave);
        }
        elseif($leave->employee->hasRole('human_resource')) {
            $role = Role::findByName('sg');
            $processing_officers = Employee::role('sg')->get();
            $leave->processing_officer_role = $role->id;
            $leave->save();
        }
        else {
            $role = Role::findByName('human_resource');
            $processing_officers = Employee::role('human_resource')->get();
            $leave->processing_officer_role = $role->id;
            $leave->save();
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
        if($employee->hasRole("human_resource")) {
            $leaves = Leave::whereNot('processing_officer_role', Role::findByName('supervisor')->id)->whereNot('processing_officer_role', Role::findByName('sg')->id)->where('leave_status', self::PENDING_STATUS)->search(request(['search']))->paginate(10);
        }
        if($employee->hasRole("supervisor")){
            $leaves = Leave::whereNot('processing_officer_role', Role::findByName('sg')->id)->where('leave_status', self::PENDING_STATUS)->whereIn('employee_id', $employee->department->employees->pluck('id')->toarray())->search(request(['search']))->paginate(10);
        }
        if($employee->hasRole("sg")) {
            $leaves = Leave::where('leave_status', self::PENDING_STATUS)->search(request(['search']))->paginate(10);
        }

        if($employee->hasRole("human_resource")) {
            $substitutes = Employee::role('human_resource')->get()->except($employee->id);
        }
        else {
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
            $processing_officer = Role::where('id', $leave->processing_officer_role)->first();
            $loggedInRole = auth()->user()->roles()->first();
            $roles = auth()->user()->getRoleNames();

            if($leave->employee_id != auth()->user()->id) {
                return view('leaves.show', [
                    'leave' => $leave,
                    'processing_officer' => $processing_officer
                ]);
            }
            foreach ($roles as $role) {
                if (Role::findByName($role)->id != $leave->processing_officer_role) {
                    return view('leaves.show', [
                        'leave' => $leave,
                        'processing_officer' => $processing_officer
                    ]);
                }
                else {
                    continue;
                }
            }
            return back();
        }
    }

    public function accept(Leave $leave) {
        if(!auth()->user()->hasRole($leave->processing_officer->name)) {
            return back();
        }
        $leave_service = new LeaveService();
        $leave_service->checkProcessingOfficerandElevateRequest($leave);
        return redirect()->route('leaves.index');
    }

    public function reject(Request $request, Leave $leave) {
        if(!auth()->user()->hasRole($leave->processing_officer->name)) {
            return back();
        }
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

    public function getCalendarForm() {
        return view('leaves.calendar-form');
    }

    public function generateCalendar(Request $request) {
        $year = now()->format('Y');
        $month = Carbon::createFromDate($year, $request->month);
        $month_name = Carbon::parse($month)->monthName;
        $start_of_month = Carbon::parse($month)->startOfMonth();
        $end_of_month = Carbon::parse($month)->endOfMonth();
        $period = CarbonPeriod::create($start_of_month, $end_of_month);
        $weekends = [];
        $holidays = [];
        $leave_service = new LeaveService();
        foreach($period as $date)
        {
            if($leave_service->isWeekend($date)) {
                $weekends[] = $date->format('Y-m-d');
            }
            if($leave_service->isHoliday($date->format('Y-m-d'))) {
                $holidays[] = $date->format('Y-m-d');
            }
            $dates[] = $date;
        }
        $leaves = Leave::whereDate('from', '<=', $end_of_month)->whereDate('to', '>=', $start_of_month)->get();
        $leaveId_dates_pairs = [];
        foreach ($leaves as $leave) {
            $period = CarbonPeriod::create($leave->from, $leave->to);
            $disabled_dates = unserialize($leave->disabled_dates);
            if($disabled_dates){
                foreach ($period as $date) {
                    $date = $date->toDateString();
                    if(!$leave_service->isWeekend($date) && !in_array($date, $disabled_dates) ){
                        $leaveId_dates_pairs[$leave->employee_id . '&' . $date] = $leave;
                    }
                }
            }
            else {
                foreach ($period as $date) {
                    $date = $date->toDateString();
                    $leaveId_dates_pairs[$leave->employee_id . '&' . $date] = $leave;
                }
            }
        }
        $employees = Employee::paginate(20);
        return view('leaves.calendar', [
            'month_name' => $month_name,
            'dates' => $dates,
            'employees' => $employees,
            'leaveId_dates_pairs' => $leaveId_dates_pairs,
            'weekends' => $weekends,
            'holidays' => $holidays,
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
