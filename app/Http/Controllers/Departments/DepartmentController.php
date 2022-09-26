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
        $old_roles = $supervisor_old->getRoleNames();
        foreach ($old_roles as $role) {
            if($role == "supervisor") {
                $old_roles_names[] = "employee";
            }
            else{
                $old_roles_names[] = $role;
            }
        }
        $supervisor_old->syncRoles($old_roles_names);

        $department->update($validated);
        $supervisor_new = Employee::where('id', $department->manager_id)->first();
        $new_roles = $supervisor_new->getRoleNames();
        foreach ($new_roles as $role) {
            if($role == "employee") {
                $new_roles_names[] = "supervisor";
            }
            else{
                $new_roles_names[] = $role;
            }
        }
        $supervisor_new->syncRoles($new_roles_names);

        return redirect()->route('departments.index');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index');
    }
}
