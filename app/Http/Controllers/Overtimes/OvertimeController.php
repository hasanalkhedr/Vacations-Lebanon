<?php

namespace App\Http\Controllers\Overtimes;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Overtime;
use App\Services\OvertimeService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class OvertimeController extends Controller
{

    const PENDING_STATUS = 0;

    public function create() {
        $employee = auth()->user();
        return view('overtimes.create',[
            'employee' => $employee,
        ]);
    }

    public function store(Request $request) {
        for ($i=0 ; $i < count($request->date); $i++) {
            if($request->date[$i] == NULL || $request->from[$i] == NULL || $request->to[$i] == NULL) {
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
            if($request->objective[$i]) {
                $overtime->objective = $request->objective[$i];
            }
            $overtime->date_of_submission = now()->format('Y-m-d');
            $overtime_employee_role = $overtime->employee->roles()->first()->name;
            if($overtime_employee_role == "employee" || $overtime_employee_role == "supervisor") {
                $role = Role::findByName('human_resource');
                $processing_officers = Employee::role('human_resource')->get();
                $overtime->processing_officer_role = $role->id;
                $overtime->save();
            }
            elseif($overtime_employee_role == "human_resource") {
                $role = Role::findByName('sg');
                $processing_officers = Employee::role('sg')->get();
                $overtime->processing_officer_role = $role->id;
                $overtime->save();
            }
            else {
                $role = Role::findByName('sg');
                $overtime->processing_officer_role = $role->id;
                $overtime->save();
                $processing_officers = NULL;
                $overtime_service->acceptLeave($overtime);
            }
            $overtime_service->sendEmailToInvolvedEmployees($overtime, $processing_officers);
        }
            return redirect()->route('overtimes.submitted');
    }

    public function submitted() {
        $overtimes = auth()->user()->overtimes;
        return view('overtimes.submitted', [
            'overtimes' => $overtimes
        ]);
    }

    public function destroy(Overtime $overtime) {
        $overtime->delete();
        return redirect()->route('overtimes.submitted');
    }

    public function index() {
        $employee=auth()->user();
        $today = now();
        $employee_role = $employee->roles()->first()->id;
        if($employee->roles()->first()->name == "supervisor"){
            $overtimes = Overtime::where('processing_officer_role', $employee_role)->where('overtime_status', self::PENDING_STATUS)->whereIn('employee_id', $employee->department->employees->pluck('id')->toarray())->search(request(['search']))->paginate(10);
        }
        else {
            $overtimes = Overtime::where('processing_officer_role', $employee_role)->where('overtime_status', self::PENDING_STATUS)->search(request(['search']))->paginate(10);
        }

        return view('overtimes.index', [
            'overtimes' => $overtimes,
            'employee' => $employee,
            'today' => $today,
            'department' => $employee->department,
        ]);
    }



    public function show(Overtime $overtime) {
        {
            $loggedInRole = auth()->user()->roles()->first();
            if($overtime->employee_id == auth()->user()->id) {
                return view('overtimes.show', [
                    'overtime' => $overtime
                ]);
            }
            elseif ($loggedInRole->id == $overtime->processing_officer_role) {
                return view('overtimes.show', [
                    'overtime' => $overtime
                ]);
            }
            else {
                return back();
            }
        }
    }

    public function accept(Overtime $overtime) {
        $overtime_service = new OvertimeService();
        $overtime_service->checkProcessingOfficerandElevateRequest($overtime);
        return redirect()->route('overtimes.index');
    }

    public function reject(Request $request, Overtime $overtime) {
        $overtime_service = new OvertimeService();
        $overtime_service->rejectLeaveRequest($request, $overtime);
        return redirect()->route('overtimes.index');
    }
}
