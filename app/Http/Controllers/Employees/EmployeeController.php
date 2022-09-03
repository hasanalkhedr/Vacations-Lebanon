<?php

namespace App\Http\Controllers\Employees;

use App\Http\Requests\EmployeesRequests\AuthenticateEmployeeRequest;
use App\Http\Requests\EmployeesRequests\StoreEmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
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
                return redirect()->route('departments.create');
            } elseif ($employee->hasRole('supervisor')) {
                return response()->json(['status' => 'OK', 'employee' => $employee, 'role' => 'supervisor']);
            } else {
                return response()->json(['status' => 'OK', 'employee' => $employee, 'role' => 'sg']);
            }
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
        if($request['department_id']) {
            $employee['department_id'] = $request['department_id'];
        }
        $employee->save();
        if($request['role_id']) {
            $role = Role::findById($request['role_id']);
            $employee->roles()->save($role);
        }
        return redirect()->route('employees.home');
    }

    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerate();
        return redirect()->route('employees.login');
    }

    public function home() {
        return view('employees.logout');
    }
}
