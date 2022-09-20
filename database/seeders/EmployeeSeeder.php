<?php

namespace Database\Seeders;

use App\Imports\EmployeeImport;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employee = Employee::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'hr@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+9613456241',
    ]);
        $role = Role::findByName('human_resource');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Secretary',
            'last_name' => 'General',
            'email' => 'sg@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176789578',
        ]);

        $role = Role::findByName('sg');
        $employee->roles()->save($role);



        $employee = Employee::create([
            'first_name' => 'Priscilla',
            'last_name' => 'Moussallem',
            'email' => 'priscilla.moussallem@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176715486',
            'department_id' => 1
        ]);
        $role = Role::findByName('supervisor');
        $employee->roles()->save($role);
        $department = Department::find(1);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => 'Carine',
            'last_name' => 'Salmane',
            'email' => 'carine.salmane@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176031593',
            'department_id' => 1
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Charbel',
            'last_name' => 'Sawaya',
            'email' => 'charbel.sawaya@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176030303',
            'department_id' => 1
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Cynthia',
            'last_name' => 'Kanaan',
            'email' => 'cynthia.kanaan@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176030307',
            'department_id' => 2
        ]);
        $role = Role::findByName('supervisor');
        $employee->roles()->save($role);
        $department = Department::find(2);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => 'Gwendoline',
            'last_name' => 'Abou Jaoude',
            'email' => 'gwendoline.aboujaoude@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176730303',
            'department_id' => 3
        ]);
        $role = Role::findByName('supervisor');
        $employee->roles()->save($role);
        $department = Department::find(3);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => 'Corinne',
            'last_name' => 'Allam',
            'email' => 'corinne.allam@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176070303',
            'department_id' => 3
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Rita',
            'last_name' => 'Hani',
            'email' => 'rita.hani@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176031403',
            'department_id' => 3
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Marielle',
            'last_name' => 'Maroun',
            'email' => 'marielle.maroun@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176030463',
            'department_id' => 4
        ]);
        $role = Role::findByName('supervisor');
        $employee->roles()->save($role);
        $department = Department::find(4);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => 'Marielle',
            'last_name' => 'Salloum',
            'email' => 'marielle.salloum@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176030163',
            'department_id' => 4
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Marie',
            'last_name' => 'Ghabril',
            'email' => 'marie.ghabril@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176030300',
            'department_id' => 5
        ]);
        $role = Role::findByName('supervisor');
        $employee->roles()->save($role);
        $department = Department::find(5);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => 'Dania',
            'last_name' => 'Ghaddar',
            'email' => 'dania.ghaddar@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176030003',
            'department_id' => 5
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Lina',
            'last_name' => 'Harake',
            'email' => 'lina.harake@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176037303',
            'department_id' => 6
        ]);
        $role = Role::findByName('supervisor');
        $employee->roles()->save($role);
        $department = Department::find(6);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => 'Camilla',
            'last_name' => 'Kaakour',
            'email' => 'camilla.kaakour@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176038903',
            'department_id' => 6
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Antoine',
            'last_name' => 'Kanaan',
            'email' => 'antoine.kanaan@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176034703',
            'department_id' => 6
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Camille',
            'last_name' => 'Legal',
            'email' => 'camille.legal@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96170030303',
            'department_id' => 6
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Jad',
            'last_name' => 'Sawma',
            'email' => 'jad.sawma@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96178030303',
            'department_id' => 6
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Jinane',
            'last_name' => 'Beydoun',
            'email' => 'jinane.beydoun@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96178470303',
            'department_id' => 7
        ]);
        $role = Role::findByName('supervisor');
        $employee->roles()->save($role);
        $department = Department::find(7);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => 'Katy',
            'last_name' => 'Abboud',
            'email' => 'katy.abboud@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96171547303',
            'department_id' => 7
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Maha',
            'last_name' => 'Hassoun',
            'email' => 'maha.hassoun@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96171549303',
            'department_id' => 7
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Blandine',
            'last_name' => 'Yazbeck',
            'email' => 'blandine.yazbeck@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176006303',
            'department_id' => 7
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Diana',
            'last_name' => 'Karaki',
            'email' => 'diana.karaki@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176493303',
            'department_id' => 8
        ]);
        $role = Role::findByName('supervisor');
        $employee->roles()->save($role);
        $department = Department::find(8);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => 'Denise',
            'last_name' => 'Melki',
            'email' => 'denise.melki@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176070003',
            'department_id' => 8
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Nicholas',
            'last_name' => 'Melki',
            'email' => 'nicholas.melki@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176030799',
            'department_id' => 8
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Herminee',
            'last_name' => 'Nurpetlian',
            'email' => 'herminee.nurpetlian@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176030993',
            'department_id' => 8
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Sandra',
            'last_name' => 'Khabazian',
            'email' => 'sandra.khabazian@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176099303',
            'department_id' => 9
        ]);
        $role = Role::findByName('supervisor');
        $employee->roles()->save($role);
        $department = Department::find(9);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => 'Lea',
            'last_name' => 'Abi Abboud',
            'email' => 'lea.abiabboud@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176943303',
            'department_id' => 9
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Elsa',
            'last_name' => 'Abou Ghazale',
            'email' => 'elsa.aboughazale@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96171111303',
            'department_id' => 9
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Fawzi',
            'last_name' => 'Hajj',
            'email' => 'fawzi.hajj@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176009093',
            'department_id' => 9
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Walid',
            'last_name' => 'Sadd',
            'email' => 'walid.saad@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176030977',
            'department_id' => 9
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Christianne',
            'last_name' => 'Safi',
            'email' => 'christian.safi@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176031543',
            'department_id' => 9
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Liliane',
            'last_name' => 'Safi',
            'email' => 'lilian.safi@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176034446',
            'department_id' => 9
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Hassane',
            'last_name' => 'Toubia',
            'email' => 'hassane.toubia@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96176014141',
            'department_id' => 9
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Antonios',
            'last_name' => 'Youssef',
            'email' => 'antonios.youssef@example.com',
            'password' => Hash::make('123456'),
            'phone_number' => '+96171170870',
            'department_id' => 9
        ]);
        $role = Role::findByName('employee');
        $employee->roles()->save($role);

    }
}
