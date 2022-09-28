<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'view_direct_employees']);
        Permission::create(['name' => 'view_all_employees']);
        Permission::create(['name' => 'view_departments']);
        Permission::create(['name' => 'view_direct_vacations']);
        Permission::create(['name' => 'view_all_vacations']);
        Permission::create(['name' => 'add_department']);
        Permission::create(['name' => 'edit_department']);
        Permission::create(['name' => 'delete_department']);
        Permission::create(['name' => 'add_employee']);
        Permission::create(['name' => 'edit_employee']);
        Permission::create(['name' => 'delete_employee']);
        Permission::create(['name' => 'accept_leave_request']);
        Permission::create(['name' => 'cancel_leave_request']);
        Permission::create(['name' => 'submit_leave_request']);
        Permission::create(['name' => 'view_leave_status']);

        $role = Role::create(['name' => 'employee']);
        $role->display_name = "Employee";
        $role->save();
        $role->givePermissionTo([
            'submit_leave_request',
            'view_leave_status',
        ]);

        $role = Role::create(['name' => 'human_resource']);
        $role->display_name = "HR";
        $role->save();
        $role->givePermissionTo([
            'view_all_employees',
            'view_departments',
            'view_all_vacations',
            'add_department',
            'edit_department',
            'delete_department',
            'add_employee',
            'edit_employee',
            'delete_employee',
            'accept_leave_request',
            'cancel_leave_request',
        ]);

        $role = Role::create(['name' => 'supervisor']);
        $role->display_name = "Supervisor";
        $role->save();
        $role->givePermissionTo([
            'submit_leave_request',
            'view_leave_status',
            'view_direct_employees',
            'view_direct_vacations',
            'accept_leave_request',
            'cancel_leave_request',
        ]);

        $role = Role::create(['name' => 'sg']);
        $role->display_name = "SG";
        $role->save();
        $role->givePermissionTo([
            'view_all_employees',
            'view_all_vacations',
            'accept_leave_request',
            'cancel_leave_request',
        ]);
    }
}
