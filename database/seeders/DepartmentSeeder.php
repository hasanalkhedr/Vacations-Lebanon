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
    }
}
