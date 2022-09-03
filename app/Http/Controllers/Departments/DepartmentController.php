<?php

namespace App\Http\Controllers\Departments;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest\StoreDepartmentRequest;
use App\Models\Department;
use App\Models\Employee;

class DepartmentController extends Controller
{
    public function create() {
        $employees = Employee::where('department_id', NULL)->role('employee')->get();
        return view('departments.create', ['employees' => $employees]);
    }

    public function store(StoreDepartmentRequest $request) {
        dd($request);
        $validate = $request->validated();
        $department = Department::create($validate);
        $department['manager_id'] = $request['id'];
        $department->save();
        return redirect()->route('employees.home');
    }

    public function index() {
        return view('departments.index', [
            'departments' => Department::all()
        ]);
    }

    public function show(Department $department) // Show single movie
    {
        $employees = Employee::where('department_id', $department->id)->get();
        return view('departments.show', [
            'department' => $department,
            'employees' => $employees
        ]);
    }
}
