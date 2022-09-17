<?php

namespace App\Http\Controllers\Departments;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequests\StoreDepartmentRequest;
use App\Http\Requests\DepartmentRequests\UpdateDepartmentRequest;
use App\Models\Department;
use App\Models\Employee;

class DepartmentController extends Controller
{
    public function create() {
        return view('departments.create');
    }

    public function store(StoreDepartmentRequest $request) {
        $validate = $request->validated();
        $department = Department::create($validate);
        $department->save();
        return redirect()->route('departments.index');
    }

    public function index() {
        return view('departments.index', [
            'departments' => Department::search(request(['search']))->paginate(10)
        ]);
    }

    public function show(Department $department)
    {
        $manager = Employee::where('id', $department->manager_id)->first();
        return view('departments.show', [
            'department' => $department,
            'employees' => $department->employees,
            'manager' => $manager
        ]);
    }

    public function edit(Department $department)
    {
        return view('departments.edit', ['department' => $department, 'employees' => $department->employees]);
    }

    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $validated = $request->validated();
        $department->update($validated);
        return redirect()->route('departments.index');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index');
    }
}
