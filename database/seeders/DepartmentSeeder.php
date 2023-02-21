<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

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
            'Department 1',
            'Department 2',
            'Department 3',
            'Department 4',
            'Department 5',
            'Department 6',
            'Department 7',
            "Department 8",
            'Department 9',
            'Department 10',
            'Department 11',
            'Department 12',
            'Department 13',
        ];
        foreach ($department_names as $name) {
            Department::create([
                'name' => $name
            ]);
        }
    }
}
