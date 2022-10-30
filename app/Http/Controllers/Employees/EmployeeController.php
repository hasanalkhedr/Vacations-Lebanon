<?php

namespace App\Http\Controllers\Employees;

use App\Helpers\Helper;
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
            if($employee->is_supervisor || $employee->hasRole("human_resource") ||$employee->hasRole("sg")){
                return redirect()->route('leaves.index');
            }
            else {
                return redirect()->route('leaves.submitted');
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
        foreach ($request->role_ids as $role_id) {
            $employee->assignRole(Role::findById($role_id)->name);
        }
        $roles = $employee->getRoleNames();
        foreach ($roles as $role) {
            $roles_names[] = $role;
        }
        if(in_array('employee', $roles_names)){
            $employee['department_id'] = $request['department_id'];
        }
        else {
            $employee['department_id'] = NULL;
        }
        $employee->save();

        return redirect()->route('employees.index');
    }

    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerate();
        return redirect()->route('employees.login');
    }

    public function index() {
        $user = auth()->user();
        $helper = new Helper();
        if($helper->checkIfNormalEmployee($user)) {
            return back();
        }
        $employees = new EmployeeService();
        $departments = Department::all();
        $roles = Role::all();
        $rolesMultiSelect = Role::get(['name', 'id'])->toArray();
        $oldkeyName = 'name';
        $newkeyName = 'label';
        $oldkeyId = 'id';
        $newkeyId = 'value';
        $i = 0;
        foreach ($rolesMultiSelect as $roleMultiSelectSingle) {
            $arrayKeys = array_keys($roleMultiSelectSingle);
            //Replace the key in our $arrayKeys array.
            $oldKeyIndexName = array_search($oldkeyName, $arrayKeys);
            $oldKeyIndexId = array_search($oldkeyId, $arrayKeys);
            $arrayKeys[$oldKeyIndexName] = $newkeyName;
            $arrayKeys[$oldKeyIndexId] = $newkeyId;
            //Combine them back into one array.
            $newArray = array_combine($arrayKeys, $roleMultiSelectSingle);
            $rolesMultiSelect[$i] = $newArray;
            $i += 1;
        }
        return view('employees.index', [
            'employees' => $employees->getAppropriateEmployees(),
            'departments' => $departments,
            'roles' => $roles,
            'rolesMultiSelect' => json_encode($rolesMultiSelect)
        ]);
    }

    public function show(Employee $employee)
    {
        $departments = Department::all();
        $roles = Role::all();
        $loggedInUser = auth()->user();
        if($loggedInUser->is_supervisor) {
            if($employee->department->id == $loggedInUser->department->id)
                return view('employees.show', [
                    'employee' => $employee,
                    'departments' => $departments,
                    'roles' => $roles
                ]);
        }
        if($loggedInUser->hasRole('human_resource') || $loggedInUser->hasRole("sg")) {
            return view('employees.show', [
                'employee' => $employee,
                'departments' => $departments,
                'roles' => $roles
            ]);
        }
        if($loggedInUser == $employee) {
            return view('employees.show', [
                'employee' => $employee,
                'departments' => $departments,
                'roles' => $roles
            ]);
        }
        return redirect()->route('employees.index');
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
        foreach ($request->role_ids as $role_id) {
            $role_names[] = Role::findById($role_id)->name;
        }
        $employee->syncRoles($role_names);
        $roles = $employee->getRoleNames();
        foreach ($roles as $role) {
            $roles_names[] = $role;
        }
        if(in_array('employee', $roles_names)){
            $employee['department_id'] = $request['department_id'];
        }
        else {
            if($employee->is_supervisor){
                $employee->department->manager_id = $request->manager_id;
                $employee->department->save();
                $new_manager = Employee::where('id', $request->manager_id)->first();
                $new_manager->is_supervisor = true;
                $new_manager->save();
            }
            $employee->is_supervisor = false;
            $employee['department_id'] = NULL;
        }
        $employee->save();
        return back();
    }

    public function editPassword(Employee $employee)
    {
        if(auth()->user()->hasRole('human_resource') || auth()->user()->id == $employee->id) {
            return view('employees.edit-password', ['employee' => $employee]);
        }
        else {
            return back();
        }
    }

    public function updatePassword(UpdateEmployeePasswordRequest $request, Employee $employee)
    {
        $validated = $request->validated();
        $employee->update(['password' => Hash::make($validated['password'])]);
        return redirect()->route('employees.show', ['employee' => $employee]);
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
