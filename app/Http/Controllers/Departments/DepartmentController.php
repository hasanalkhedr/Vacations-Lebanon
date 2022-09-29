<?php

namespace App\Http\Controllers\Departments;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequests\StoreDepartmentRequest;
use App\Http\Requests\DepartmentRequests\UpdateDepartmentRequest;
use App\Models\Department;
use App\Models\Employee;
use Spatie\Permission\Models\Role;

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
        return view('departments.show', [
            'department' => $department,
            'employees' => $department->employees,
            'manager' => $department->manager
        ]);
    }


    public function edit(Department $department)
    {
        return view('departments.edit', ['department' => $department, 'employees' => $department->employees]);
    }

    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $validated = $request->validated();
        $supervisor_old = Employee::where('id', $department->manager_id)->first();
        $supervisor_old->is_supervisor = false;
        $supervisor_old->save();
        $department->update($validated);
        $supervisor_new = Employee::where('id', $department->manager_id)->first();
        $supervisor_new->is_supervisor = true;
        $supervisor_new->save();

        return back();
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index');
    }
}
