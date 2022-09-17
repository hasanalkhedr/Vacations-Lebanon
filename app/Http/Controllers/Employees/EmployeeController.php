<?php

namespace App\Http\Controllers\Employees;

use App\Http\Requests\EmployeesRequests\AuthenticateEmployeeRequest;
use App\Http\Requests\EmployeesRequests\StoreEmployeeRequest;
use App\Http\Requests\EmployeesRequests\UpdateEmployeePasswordRequest;
use App\Http\Requests\EmployeesRequests\UpdateEmployeeProfileRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployeeController
{
    public function login() {
        return view('employees.login');
    }
    public function authenticate(AuthenticateEmployeeRequest $request) {

        $validated = $request->validated();

        if (auth()->attempt($validated)) {
            $employee = auth()->user();
            $request->session()->regenerate();
            if ($employee->hasRole('employee')) {
                return response()->json(['status' => 'OK', 'employee' => $employee, 'role' => 'employee']);
            } elseif ($employee->hasRole('human_resource')) {
                return redirect()->route('departments.index');
            } elseif ($employee->hasRole('supervisor')) {
                return response()->json(['status' => 'OK', 'employee' => $employee, 'role' => 'supervisor']);
            } else {
                return response()->json(['status' => 'OK', 'employee' => $employee, 'role' => 'sg']);
            }
        }
        else {
            return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
        }
    }

    public function create() {
        $departments = Department::all();
        $roles = Role::all();
        return view('employees.create', ['departments' => $departments, 'roles' => $roles]);
    }

    public function store(StoreEmployeeRequest $request) {
        $validated = $request->validated();
        $employee = Employee::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone_number' => $validated['phone_number'],
            'nb_of_days' => $validated['nb_of_days'],
        ]);
        $role = Role::findById($request['role_id']);
        if($role->name != 'employee'){
            $employee['department_id'] = NULL;
        }
        else {
            $employee['department_id'] = $request['department_id'];
        }
        $employee->save();

        $employee->roles()->save($role);
        return redirect()->route('employees.index');
    }

    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerate();
        return redirect()->route('employees.login');
    }

    public function index() {
        $employees = new EmployeeService();
        $departments = Department::all();
        $roles = Role::all();
        return view('employees.index', [
            'employees' => $employees->getAppropriateEmployees(),
            'departments' => $departments,
            'roles' => $roles
        ]);
    }

    public function show(Employee $employee)
    {
        $loggedInUser = auth()->user();
        $loggedInUserRoleName = $loggedInUser->roles()->first()->name;
        if($loggedInUserRoleName == "supervisor") {
            if($employee->department->id == $loggedInUser->department->id)
                return view('employees.show', [
                    'employee' => $employee
                ]);
        }
        if($loggedInUserRoleName == "human_resource" || $loggedInUserRoleName == "sg") {
            return view('employees.show', [
                'employee' => $employee
            ]);
        }
        if($loggedInUser == $employee) {
            return view('employees.show', [
                'employee' => $employee
            ]);
        }
        return back();
    }

    public function editProfile(Employee $employee)
    {
        $departments = Department::all();
        $roles = Role::all();
        return view('employees.edit-profile', ['employee' => $employee, 'departments' => $departments, 'roles' => $roles]);
    }

    public function updateProfile(UpdateEmployeeProfileRequest $request, Employee $employee)
    {
        $validated = $request->validated();
        $employee->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'nb_of_days' => $validated['nb_of_days'],
        ]);
        $role = Role::findById($request['role_id']);
        $employee->roles()->sync([$validated['role_id']]);
        if($role->name != 'employee'){
            $employee['department_id'] = NULL;
        }
        else {
            $employee['department_id'] = $request['department_id'];
        }
        $employee->save();
        return redirect()->route('employees.index');
    }

    public function editPassword(Employee $employee)
    {
        if(auth()->user()->roles()->first()->name == 'human_resource' || auth()->user()->id == $employee->id) {
            return view('employees.edit-password', ['employee' => $employee]);
        }
        else {
            return back();
        }
    }

    public function updatePassword(UpdateEmployeePasswordRequest $request, Employee $employee)
    {
        $validated = $request->validated();
        if(Hash::check($validated['current_password'], $employee->password)) {
            $employee->update(['password' => Hash::make($validated['new_password'])]);
            return redirect()->route('employees.show', ['employee' => $employee]);
        }
        else {
            return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('new_password');
        }
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index');
    }


    public function home() {
        return view('employees.logout');
    }
}
