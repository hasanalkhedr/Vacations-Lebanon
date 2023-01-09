<?php

namespace App\Http\Controllers\Leaves;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequests\StoreLeaveRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveDuration;
use App\Models\LeaveType;
use App\Services\EmployeeService;
use App\Services\OvertimeService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use App\Services\LeaveService;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    const PENDING_STATUS = 0;
    const ACCEPTED_STATUS = 1;
    const REJECTED_STATUS = 2;

    public function create() {
        $employee = auth()->user();
        if(($employee->hasExactRoles("employee") || $employee->hasAllRoles(['employee','human_resource'])) && $employee->is_supervisor == false) {
            $employee_service = new EmployeeService();
            $helper = new Helper();
            $normal_pending_days = $employee_service->getNormalNbofDaysPending($employee);
            $confessionnel_pending_days = $employee_service->getConfessionnelNbofDaysPending($employee);
            $normal_accepted_days = $employee_service->getNormalNbofDaysAccepted($employee);
            $confessionnel_accepted_days = $employee_service->getConfessionnelNbofDaysAccepted($employee);
            $leave_durations = LeaveDuration::all();
            $leave_types = LeaveType::all();
            $today = now();
            $substitutes = Employee::where('department_id', $employee->department_id)->where('is_supervisor', false)->get()->except($employee->id);
            $leave_service = new LeaveService();
            $disabled_dates = $leave_service->getDisabledDates($employee);
            $holiday_dates = $helper->getHolidays();
            $confessionnel_dates = $leave_service->getConfessionnels();
            return view('leaves.create', [
                'employee' => $employee,
                'leave_durations' => $leave_durations,
                'leave_types' => $leave_types,
                'today' => $today,
                'department' => $employee->department,
                'substitutes' => $substitutes,
                'disabled_dates' => $disabled_dates,
                'holiday_dates' => $holiday_dates,
                'confessionnel_dates' => $confessionnel_dates,
                'normal_pending_days' => $normal_pending_days,
                'confessionnel_pending_days' => $confessionnel_pending_days,
                'normal_accepted_days' => $normal_accepted_days,
                'confessionnel_accepted_days' => $confessionnel_accepted_days,
            ]);
        }
        else {
            return back();
        }
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
        if($request->mix_of_leaves) {
            $leave->mix_of_leaves = true;
        }
        if($request->hasFile('attachment_path')) {
            $leave['attachment_path'] = $request->file('attachment_path')->store('attachments', 'public');
        }
        $leave->date_of_submission = now()->format('Y/m/d');
        if($request['substitute_employee_id']) {
            $leave->substitute_employee_id = $request['substitute_employee_id'];
        }
        $leave->disabled_dates = $serializedArr;

        if($leave->employee->hasAllRoles(['employee','human_resource']) && $leave->employee->is_supervisor == false) {
            $role = Role::findByName('sg');
            $processing_officers = Employee::role('sg')->get();
            $leave->processing_officer_role = $role->id;
        }
        else if($leave->employee->department->manager->hasRole('sg')) {
            $role = Role::findByName('human_resource');
            $processing_officers = Employee::role('human_resource')->get();
            $leave->processing_officer_role = $role->id;
        }
        else {
            $role = Role::findByName('employee');
            $processing_officers = collect([auth()->user()->department->manager]);
            $leave->processing_officer_role = $role->id;
        }
        $leave->save();
        // $leave_service->sendEmailToInvolvedEmployees($leave, $processing_officers, $leave->substitute_employee);
        return redirect()->route('leaves.submitted');
    }

    public function index() {
        $employee=auth()->user();
        $helper = new Helper();
        if($helper->checkIfNormalEmployee($employee)) {
            return back();
        }
        if($employee->hasRole("employee") && $employee->is_supervisor){
            $leaves = Leave::where('processing_officer_role', Role::findByName('employee')->id)->where('leave_status', self::PENDING_STATUS)
                ->whereHas('employee', function ($q) use ($employee) {
                    $q->whereHas('department', function ($q) use ($employee) {
                        $q->where('manager_id', $employee->id);
                    });
                })
                ->whereNot('employee_id', $employee->id)
                ->search(request(['search']))->paginate(10);
        }
        if($employee->hasRole("human_resource")) {
            $leaves = Leave::whereNot('processing_officer_role', Role::findByName('sg')->id)->where('leave_status', self::PENDING_STATUS)->search(request(['search']))->paginate(10);
        }
        if($employee->hasRole("sg")) {
            $leaves = Leave::where('leave_status', self::PENDING_STATUS)->search(request(['search']))->paginate(10);
        }
        return view('leaves.index', [
            'leaves' => $leaves,
            'employee' => $employee,
        ]);
    }

    public function acceptedIndex() {
        $employee = auth()->user();
        $helper = new Helper();
        if($helper->checkIfNormalEmployee($employee)) {
            return back();
        }

        if($employee->hasExactRoles('employee') && $employee->is_supervisor) {
            $leaves = Leave::where('leave_status', self::ACCEPTED_STATUS)
                        ->orWhere(function ($query) use ($employee) {
                            $query->whereHas('employee', function ($q) use ($employee) {
                                $q->whereHas('department', function ($q) use ($employee) {
                                    $q->where('manager_id', $employee->id);
                                });})
                                ->whereNot('processing_officer_role', Role::findByName('employee')->id)
                                ->where('leave_status', self::PENDING_STATUS);})
                                ->whereNot('employee_id', $employee->id)
                                ->paginate(10);
        }

        if($employee->hasRole('human_resource')) {
            $leaves = Leave::where('leave_status', self::ACCEPTED_STATUS)
                            ->orWhere(function ($query) {
                             $query->where('leave_status', self::PENDING_STATUS)->where('processing_officer_role', Role::findByName('sg')->id);})
                            ->whereNot('employee_id', $employee->id)
                            ->paginate(10);
        }

        if($employee->hasRole('sg')) {
            $leaves = Leave::whereNot('employee_id', $employee->id)
                        ->where('leave_status', self::ACCEPTED_STATUS)->paginate(10);

        }

        return view('leaves.accepted-index', [
            'leaves' => $leaves
        ]);
    }

    public function rejectedIndex() {
        $employee = auth()->user();
        $helper = new Helper();
        if($helper->checkIfNormalEmployee($employee)) {
            return back();
        }
        $leaves = Leave::where('leave_status', self::REJECTED_STATUS)->where('rejected_by', $employee->id)->whereNot('employee_id', $employee->id)->paginate(10);
        return view('leaves.rejected-index', [
            'leaves' => $leaves
        ]);
    }

    public function show(Leave $leave) {
        {
            $loggedInUser = auth()->user();
            $processing_officer = Role::where('id', $leave->processing_officer_role)->first();

            if($leave->employee_id == $loggedInUser->id || $loggedInUser->hasRole(['human_resource', 'sg']) || $loggedInUser->id == $leave->employee->department->manager_id) {
                return view('leaves.show', [
                    'leave' => $leave,
                    'processing_officer' => $processing_officer
                ]);
            }

            return back();
        }
    }

    public function accept(Leave $leave) {
        $employee=auth()->user();
        $helper = new Helper();
        if($helper->checkIfNormalEmployee($employee)) {
            return back();
        }
        if(!$employee->hasRole($leave->processing_officer->name)) {
            return back();
        }
        $leave_service = new LeaveService();
        $leave_service->checkProcessingOfficerandElevateRequest($leave);
        return redirect()->route('leaves.index');
    }

    public function reject(Request $request, Leave $leave) {
        $employee=auth()->user();
        $helper = new Helper();
        if($helper->checkIfNormalEmployee($employee) || !$employee->hasRole($leave->processing_officer->name)) {
            return back();
        }
        $leave_service = new LeaveService();
        $leave_service->rejectLeaveRequest($request, $leave);
        return redirect()->route('leaves.index');
    }

    public function submitted() {
        if((auth()->user()->hasExactRoles("employee") || auth()->user()->hasAllRoles(['employee','human_resource'])) && auth()->user()->is_supervisor == false) {
            $leaves = Leave::where('employee_id', auth()->user()->id)->paginate(10);
            return view('leaves.submitted', [
                'leaves' => $leaves
            ]);
        }
        else {
            return back();
        }

    }

    public function getCalendarForm() {
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
        $months = [];

        for ($monthNum=1; $monthNum<=12; $monthNum++) {
            $dateObj = \DateTime::createFromFormat('!m', $monthNum);
            $months[] = [$dateObj->format('m'), $dateObj->format('F')];
        }
        $departments = Department::all();
        return view('leaves.calendar-form', [
            'departments' => $departments,
            'months' => $months
        ]);
    }

    public function generateCalendar(Request $request) {
        $helper = new Helper();
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
            if($helper->isWeekend($date)) {
                $weekends[] = $date->format('Y-m-d');
            }
            if($helper->isHoliday($date->format('Y-m-d'))) {
                $holidays[] = $date->format('Y-m-d');
            }
            $dates[] = $date;
        }
        if($request->department_id == 'all') {
            $leaves = Leave::whereNot('leave_status', self::REJECTED_STATUS)->whereDate('from', '<=', $end_of_month)->whereDate('to', '>=', $start_of_month)->get();
            $employees = Employee::all();
        }
        else {
            if(!auth()->user()->hasRole(['human_resource', 'sg'])){
                $department = Department::where('id', auth()->user()->department_id)->first();
            }
            else {
                $department = Department::where('id', $request->department_id)->first();
            }
            $leaves = Leave::whereIn('employee_id', $department->employees->pluck('id')->toarray())->whereNot('leave_status', self::REJECTED_STATUS)->whereDate('from', '<=', $end_of_month)->whereDate('to', '>=', $start_of_month)->get();
            $employees = Employee::where('department_id', $department->id)->where('is_supervisor', false)->get();
        }
        $leaveId_dates_pairs = [];
        foreach ($leaves as $leave) {
            $period = CarbonPeriod::create($leave->from, $leave->to);
            $disabled_dates = unserialize($leave->disabled_dates);
            if($disabled_dates){
                foreach ($period as $date) {
                    $date = $date->toDateString();
                    if(!$helper->isWeekend($date) && !in_array($date, $disabled_dates) ){
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
        return view('leaves.calendar', [
            'month_name' => $month_name,
            'dates' => $dates,
            'employees' => $employees,
            'leaveId_dates_pairs' => $leaveId_dates_pairs,
            'weekends' => $weekends,
            'holidays' => $holidays,
        ]);
    }

    public function downloadAttachment(Leave $leave) {
        $path = Storage::disk('local')->path("public/$leave->attachment_path");
        $content = file_get_contents($path);
        return response($content)->withHeaders([
            'Content-Type' => mime_content_type($path)
        ]);
    }

    public function destroy(Leave $leave) {
        $processing_officers=[];
        if($leave->employee->hasRole('employee') && $leave->employee->is_supervisor == false) {
            $supervisor = $leave->employee->department->manager;
            $processing_officers[] = $supervisor;
            $hrs = Employee::role('human_resource')->get();
            foreach ($hrs as $hr) {
                $processing_officers[] = $hr;
            }
            $sgs = Employee::role('sg')->get();
            foreach ($sgs as $sg) {
                $processing_officers[] = $sg;
            }
        }
        elseif($leave->employee->hasRole('employee') && $leave->employee->is_supervisor) {
            $hrs = Employee::role('human_resource')->get();
            foreach ($hrs as $hr) {
                $processing_officers[] = $hr;
            }
            $sgs = Employee::role('sg')->get();
            foreach ($sgs as $sg) {
                $processing_officers[] = $sg;
            }
        }
        else {
        $sgs = Employee::role('sg')->get();
            foreach ($sgs as $sg) {
                $processing_officers[] = $sg;
            }
        }
        $leave_service = new LeaveService();
        // $leave_service->sendEmailToInvolvedEmployees($leave, $processing_officers, $leave->substitute_employee, true);
        $leave->delete();
        return redirect()->route('leaves.submitted');
    }

    public function createReport() {
        if(auth()->user()->hasRole(['human_resource', 'sg'])) {
            $employees = Employee::role('employee')->where('is_supervisor', false)->orderBy('first_name')->get();
            return view('leaves.create-report', [
                'employees' => $employees
            ]);
        }
        else {
            return back();
        }
    }

    public function generateReport(Request $request) {
        $employee_id = $request->employee_id;
        $employee = Employee::whereId($employee_id)->first();
        $from_date = Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d');
        $to_date = Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d');
        $leave_service = new LeaveService();
        $data = $leave_service->fetchLeaves($employee_id, $from_date, $to_date);
        $leaves = $data['leaves'];
        unset($data['leaves']);

        return view('leaves.view-report', [
            'leaves' => $leaves,
            'employee' => $employee,
            'data' => $data
        ]);
    }

}
