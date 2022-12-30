<?php

namespace App\Http\Controllers\Overtimes;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Overtime;
use App\Services\OvertimeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class OvertimeController extends Controller
{
    const PENDING_STATUS = 0;
    const ACCEPTED_STATUS = 1;
    const REJECTED_STATUS = 2;

    public function create() {
        $helper = new Helper();
        $employee = auth()->user();
        if(($employee->hasExactRoles("employee") || $employee->hasAllRoles(['employee','human_resource'])) && $employee->is_supervisor == false) {
            $holiday_dates = $helper->getHolidays();
            return view('overtimes.create',[
                'employee' => $employee,
                'holiday_dates' => $holiday_dates,
            ]);
        }
        else {
            return back();
        }

    }

    public function store(Request $request) {
        if($request->has('date')) {
            for ($i = 0; $i < count($request->date); $i++) {
                if ($request->date[$i] == NULL || $request->from[$i] == NULL || $request->to[$i] == NULL) {
                    continue;
                }
                $overtime_service = new OvertimeService();
                $overtime = Overtime::create([
                    'employee_id' => auth()->user()->id,
                    'date' => $request->date[$i],
                    'from' => $request->from[$i],
                    'to' => $request->to[$i],
                    'hours' => $request->hours[$i]
                ]);
                if ($request->objective[$i]) {
                    $overtime->objective = $request->objective[$i];
                }
                $overtime->date_of_submission = now()->format('Y-m-d');
                if($overtime->employee->hasExactRoles('employee') && $overtime->employee->is_supervisor == false) {
                    $role = Role::findByName('employee');
                    $processing_officers = auth()->user()->department->manager;
                    $overtime->processing_officer_role = $role->id;
                }
                else if($overtime->employee->hasAllRoles(['employee','human_resource']) && $overtime->employee->is_supervisor == false) {
                    $role = Role::findByName('sg');
                    $processing_officers = Employee::role('sg')->get();
                    $overtime->processing_officer_role = $role->id;
                }
                $overtime->save();
                // $overtime_service->sendEmailToInvolvedEmployees($overtime, $processing_officers);
            }
        }
        return back();
    }

    public function submitted() {
        if(auth()->user()->hasExactRoles("employee") && auth()->user()->is_supervisor == false) {
            $overtimes = auth()->user()->overtimes;
            return view('overtimes.submitted', [
                'overtimes' => $overtimes
            ]);
        }
        else {
            return back();
        }
    }

    public function destroy(Overtime $overtime) {
        $overtime->delete();
        return redirect()->route('overtimes.submitted');
    }

    public function index() {
        $employee=auth()->user();
        $helper = new Helper();
        if($helper->checkIfNormalEmployee($employee)) {
            return back();
        }
        $today = now();
        if($employee->hasRole("human_resource")) {
            $overtimes = Overtime::whereNot('processing_officer_role', Role::findByName('employee')->id)->whereNot('processing_officer_role', Role::findByName('sg')->id)->where('overtime_status', self::PENDING_STATUS)->search(request(['search']))->paginate(10);
        }
        if($employee->hasRole("employee")  && $employee->is_supervisor){
            $overtimes = Overtime::whereNot('processing_officer_role', Role::findByName('sg')->id)->where('overtime_status', self::PENDING_STATUS)->whereIn('employee_id', $employee->department->employees->pluck('id')->toarray())->search(request(['search']))->paginate(10);
        }
        if($employee->hasRole("sg")) {
            $overtimes = Overtime::where('overtime_status', self::PENDING_STATUS)->search(request(['search']))->paginate(10);
        }

        return view('overtimes.index', [
            'overtimes' => $overtimes,
            'employee' => $employee,
            'today' => $today,
            'department' => $employee->department,
        ]);
    }

    public function acceptedIndex() {
        $ROLES_ASCENDING = array(Role::findByName('employee')->id, Role::findByName('human_resource')->id, Role::findByName('sg')->id);
        $employee=auth()->user();
        $overtimes = Overtime::where('overtime_status', self::REJECTED_STATUS)->orWhere('processing_officer' , ">", array_search(Role::findByName($employee->getRoleNames()->first()->name)->id, $ROLES_ASCENDING));
        return view('overtimes.accepted-index', [
            'overtimes' => $overtimes
        ]);
    }

    public function rejectedIndex() {
        $employee=auth()->user();
        $overtimes = Overtime::where('overtime_status', self::REJECTED_STATUS)->where('processing_officer', $employee->id);
        return view('overtimes.rejected-index', [
            'overtimes' => $overtimes
        ]);
    }

    public function show(Overtime $overtime) {
        {
            $processing_officer = Role::where('id', $overtime->processing_officer_role)->first();
            $roles = auth()->user()->getRoleNames();
            if($overtime->employee_id == auth()->user()->id) {
                return view('overtimes.show', [
                    'overtime' => $overtime,
                    'processing_officer' => $processing_officer
                ]);
            }
            foreach ($roles as $role) {
                if (Role::findByName($role)->id == $overtime->processing_officer_role){
                    return view('overtimes.show', [
                        'overtime' => $overtime,
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

    public function accept(Overtime $overtime) {
        $user = auth()->user();
        $helper = new Helper();
        if($helper->checkIfNormalEmployee($user)) {
            return back();
        }
        if(!$user->hasRole($overtime->processing_officer->name)) {
            return back();
        }
        $overtime_service = new OvertimeService();
        $overtime_service->checkProcessingOfficerandElevateRequest($overtime);
        return redirect()->route('overtimes.index');
    }

    public function reject(Request $request, Overtime $overtime) {
        $user = auth()->user();
        $helper = new Helper();
        if($helper->checkIfNormalEmployee($user)) {
            return back();
        }
        if(!$user->hasRole($overtime->processing_officer->name)) {
            return back();
        }
        $overtime_service = new OvertimeService();
        $overtime_service->rejectLeaveRequest($request, $overtime);
        return redirect()->route('overtimes.index');
    }

    public function createReport() {
        $employees = Employee::role('employee')->where('is_supervisor', false)->orderBy('first_name')->get();
        return view('overtimes.create-report', [
            'employees' => $employees
        ]);
    }

    public function generateReport(Request $request) {
        $employee_id = $request->employee_id;
        $employee = Employee::whereId($employee_id)->first();
        $from_date = Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d');
        $to_date = Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d');
        $overtime_service = new OvertimeService();
        $data = $overtime_service->fetchOvertimes($employee_id, $from_date, $to_date);
        $overtimes = $data['overtimes'];
        $total_time = $data['total_time'];

        return view('overtimes.view-report', [
            'overtimes' => $overtimes,
            'employee' => $employee,
            'total_time' => $total_time
        ]);
    }
}
