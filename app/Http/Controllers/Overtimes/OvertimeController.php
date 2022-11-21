<?php

namespace App\Http\Controllers\Overtimes;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Overtime;
use App\Services\OvertimeService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class OvertimeController extends Controller
{
    const PENDING_STATUS = 0;
    const ACCEPTED_STATUS = 1;
    const REJECTED_STATUS = 2;

    public function create() {
        $overtime_service = new OvertimeService();
        $employee = auth()->user();
        if($employee->hasAnyRole(['human_resource', 'sg']) || $employee->is_supervisor) {
            return back();
        }
        $holiday_dates = $overtime_service->getHolidays();
        return view('overtimes.create',[
            'employee' => $employee,
            'holiday_dates' => $holiday_dates,
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
            $role = Role::findByName('employee');
            $processing_officers = auth()->user()->department->manager;
            $overtime->processing_officer_role = $role->id;
            $overtime->save();
//            $overtime_service->sendEmailToInvolvedEmployees($overtime, $processing_officers);
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
        $leaves = Overtime::where('leave_status', self::REJECTED_STATUS)->orWhere('processing_officer' , ">", array_search(Role::findByName($employee->getRoleNames()->first()->name)->id, $ROLES_ASCENDING));
        return view('leaves.acceptedIndex', [
            'leaves' => $leaves
        ]);
    }

    public function rejectedIndex() {
        $employee=auth()->user();
        $leaves = Overtime::where('overtime_status', self::REJECTED_STATUS)->where('processing_officer', $employee->id);
        return view('leaves.rejectedIndex', [
            'leaves' => $leaves
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
}
