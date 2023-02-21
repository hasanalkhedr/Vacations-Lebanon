<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployeeSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Agence Compatable

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 1
        ]);
        $role = Role::findByName('employee');
        $employee->is_supervisor = true;
        $employee->can_submit_requests = false;
        $employee->save();
        $employee->roles()->save($role);
        $department = Department::find(1);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 1
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 1
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Audio

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 2
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        //Bekaa

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 3
        ]);
        $role = Role::findByName('employee');
        $employee->is_supervisor = true;
        $employee->can_submit_requests = false;
        $employee->save();
        $employee->roles()->save($role);
        $department = Department::find(3);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 3
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 3
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Bureau du Livre

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'phone_number' => '+96176030300',
            'department_id' => 4
        ]);
        $role = Role::findByName('employee');
        $employee->is_supervisor = true;
        $employee->can_submit_requests = false;
        $employee->save();
        $employee->roles()->save($role);
        $department = Department::find(4);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 4
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 4
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);


        // Campus France

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 5
        ]);
        $role = Role::findByName('employee');
        $employee->is_supervisor = true;
        $employee->can_submit_requests = false;
        $employee->save();
        $employee->roles()->save($role);
        $department = Department::find(5);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 5
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Centre de Langes

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 6
        ]);
        $role = Role::findByName('employee');
        $employee->is_supervisor = true;
        $employee->can_submit_requests = false;
        $employee->save();
        $employee->roles()->save($role);
        $department = Department::find(6);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 6
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 6
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Communication

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 7
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Culturel

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 8
        ]);
        $role = Role::findByName('employee');
        $employee->is_supervisor = true;
        $employee->can_submit_requests = false;
        $employee->save();
        $employee->roles()->save($role);
        $department = Department::find(8);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 8
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Deir El Qamar

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 9
        ]);
        $role = Role::findByName('employee');
        $employee->is_supervisor = true;
        $employee->can_submit_requests = false;
        $employee->save();
        $employee->roles()->save($role);
        $department = Department::find(9);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 9
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 9
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->roles()->save($role);

        // Direction

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 10
        ]);
        $role = Role::findByName('employee');
        $employee->is_supervisor = true;
        $employee->can_submit_requests = false;
        $employee->save();
        $employee->roles()->save($role);
        $department = Department::find(2);
        $department['manager_id'] = $employee['id'];
        $department->save();
        $department = Department::find(7);
        $department['manager_id'] = $employee['id'];
        $department->save();
        $department = Department::find(10);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 10
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Jounieh

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 11
        ]);
        $role = Role::findByName('employee');
        $employee->is_supervisor = true;
        $employee->can_submit_requests = false;
        $employee->save();
        $employee->roles()->save($role);
        $department = Department::find(11);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 11
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Linguistique

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 12
        ]);
        $role = Role::findByName('employee');
        $employee->is_supervisor = true;
        $employee->can_submit_requests = false;
        $employee->save();
        $employee->roles()->save($role);
        $department = Department::find(12);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 12
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Secrétariat Général

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 13
        ]);
        $role = Role::findByName('employee');
        $employee->is_supervisor = true;
        $employee->can_submit_requests = false;
        $employee->save();
        $employee->roles()->save($role);
        $role = Role::findByName('sg');
        $employee->roles()->save($role);
        $department = Department::find(13);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 13
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 13
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);
        $role = Role::findByName('human_resource');
        $employee->roles()->save($role);
        $employee->can_submit_requests = true;
        $employee->save();

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 13
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => Hash::make('123456'),
            'department_id' => 13
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

    }
}
