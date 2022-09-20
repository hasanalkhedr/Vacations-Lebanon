<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $department_names = [
            'AGENCE COMPTABLE',
            'AUDIOVISUEL',
            'CAMPUS FRANCE',
            'COMMUNICATION',
            'COURS DE LANGUE',
            'CULTUREL',
            'LINGUISTIQUE',
            'MEDIATHEQUE',
            'SECRETARIAT GENERALE'
        ];
        foreach ($department_names as $name) {
            Department::create([
                'name' => $name
            ]);
        }
//        Department::factory(20)->create()->each(function ($department) {
//            $employee = Employee::create([
//                'first_name' => 'Supervisor',
//                'last_name' => 'N1',
//                'email' => 'supervisor1@example.com',
//                'password' => Hash::make('123456'),
//                'phone_number' => '+96176030303',
//                'departemnt_id' => $department['id']
//            ]);
//            $employee->assignRole('supervisor');
//        });
    }
}
