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
            'first_name' => 'Catherine',
            'last_name' => 'Lapierre',
            'email' => 'catherine.lapierre@diplomatie.gouv.fr',
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
            'first_name' => 'Carine',
            'last_name' => 'Salmane',
            'email' => 'carine.salmane@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 1
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Charbel',
            'last_name' => 'Sawaya',
            'email' => 'charbel.sawaya@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 1
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Audio

        $employee = Employee::create([
            'first_name' => 'Cynthia',
            'last_name' => 'Kanaan',
            'email' => 'cynthia.kanaan@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 2
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        //Bekaa

        $employee = Employee::create([
            'first_name' => 'Camille',
            'last_name' => 'Brunel',
            'email' => 'camille.brunel@if-liban.com',
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
            'first_name' => 'Dolly',
            'last_name' => 'Bermont',
            'email' => 'dolly.bermont@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 3
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Josette',
            'last_name' => 'Abboud',
            'email' => 'josette.abboud@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 3
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Ali',
            'last_name' => 'Alaeddine',
            'email' => 'ali.alaeddine@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 3
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Rima',
            'last_name' => 'Mourtada',
            'email' => 'rima.mourtada@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 3
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Bureau du Livre

        $employee = Employee::create([
            'first_name' => 'Mathieu',
            'last_name' => 'Diez',
            'email' => 'mathieu.diez@diplomatie.gouv.fr',
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
            'first_name' => 'Denise',
            'last_name' => 'Melki',
            'email' => 'denise.melki@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 4
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Diana',
            'last_name' => 'Karaki',
            'email' => 'diana.dilandji@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 4
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Nicolas',
            'last_name' => 'Melki',
            'email' => 'nicolas.melki@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 4
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Herminée',
            'last_name' => 'Nurpetlian',
            'email' => 'hermine.nurpetlian@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 4
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Campus France

        $employee = Employee::create([
            'first_name' => 'Agnès',
            'last_name' => 'De Geoffroy',
            'email' => 'agnes.de-geoffroy@diplomatie.gouv.fr',
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
            'first_name' => 'Gwendoline',
            'last_name' => 'Abou Jaoude',
            'email' => 'gwendoline.aboujaoude@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 5
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Corrine',
            'last_name' => 'Allam',
            'email' => 'corinne.allam@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 5
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Rita',
            'last_name' => 'Hani',
            'email' => 'rita.hani@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 5
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Centre de Langes

        $employee = Employee::create([
            'first_name' => 'Camille',
            'last_name' => 'Le Gal',
            'email' => 'camille.legal@if-liban.com',
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
            'first_name' => 'Dania',
            'last_name' => 'Ghaddar',
            'email' => 'dania.ghaddar@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 6
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Marie',
            'last_name' => 'Ghabril',
            'email' => 'marie.ghabril@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 6
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Lina',
            'last_name' => 'Harake',
            'email' => 'denise.melki@example.com',
            'password' => Hash::make('123456'),
            'department_id' => 6
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Antoine',
            'last_name' => 'Kanaan',
            'email' => 'tony.kanaan@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 6
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Jad',
            'last_name' => 'Sawma',
            'email' => 'jad.sawma@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 6
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Communication

        $employee = Employee::create([
            'first_name' => 'Marielle',
            'last_name' => 'Maroun',
            'email' => 'marielle.salloum@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 7
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Culturel

        $employee = Employee::create([
            'first_name' => 'Isabelle',
            'last_name' => 'Seigneur',
            'email' => 'isabelle.seigneur@diplomatie.gouv.fr',
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
            'first_name' => 'Jinane',
            'last_name' => 'Beydoun',
            'email' => 'jinane.beydoun@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 8
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Deir El Qamar

        $employee = Employee::create([
            'first_name' => 'Zara',
            'last_name' => 'Fournier',
            'email' => 'zara.fournier@if-liban.com',
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
            'first_name' => 'Hiam',
            'last_name' => 'Azzi',
            'email' => 'hiam.azze@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 9
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Josephine',
            'last_name' => 'Boumrad',
            'email' => 'josephine.abourjeily@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 9
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Samer',
            'last_name' => 'Chamseddine',
            'email' => 'samer.chamseddine@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 9
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Carmen',
            'last_name' => 'Hayek',
            'email' => 'carmen.hayek@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 9
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Direction

        $employee = Employee::create([
            'first_name' => 'Guillaume',
            'last_name' => 'Duchemin',
            'email' => 'guillaume.duchemin@diplomatie.gouv.fr',
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
            'first_name' => 'Sabine',
            'last_name' => 'Sciortino',
            'email' => 'sabine.sciortino@diplomatie.gouv.fr',
            'password' => Hash::make('123456'),
            'department_id' => 10
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Jounieh

        $employee = Employee::create([
            'first_name' => 'Mélodie',
            'last_name' => 'Bardin',
            'email' => 'melodie.bardin@if-liban.com',
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
            'first_name' => 'Christelle',
            'last_name' => 'Fadel Pierret',
            'email' => 'christelle.fadel-pierret@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 11
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Claudine',
            'last_name' => 'Mrad',
            'email' => 'claudine.mrad@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 11
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Sarah',
            'last_name' => 'Hobeika',
            'email' => 'sarah.hobeika@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 11
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Linguistique

        $employee = Employee::create([
            'first_name' => 'Cécile',
            'last_name' => 'Saint Martin',
            'email' => 'cecile.saint-martin@diplomatie.gouv.fr',
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
            'first_name' => 'Katy',
            'last_name' => 'Abboud',
            'email' => 'ketty.abboud@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 12
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Maha',
            'last_name' => 'Hasoun',
            'email' => 'maha.hassoun@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 12
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Secrétariat Général

        $employee = Employee::create([
            'first_name' => 'Mélanie',
            'last_name' => 'Bouchard',
            'email' => 'melanie.bouchard@diplomatie.gouv.fr',
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
            'first_name' => 'Elsa',
            'last_name' => 'Abou Ghazale',
            'email' => 'elsa.abou-ghazale@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 13
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Fawzi',
            'last_name' => 'El-Hajj',
            'email' => 'fawzi.elhajj@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 13
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Sandra',
            'last_name' => 'Khabazian',
            'email' => 'sandra.khabazian@if-liban.com',
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
            'first_name' => 'Léa',
            'last_name' => 'Abi Abboud',
            'email' => 'Lea.abi-abboud@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 13
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Walid',
            'last_name' => 'Saad',
            'email' => 'walid.saad@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 13
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Christiane',
            'last_name' => 'Safi',
            'email' => 'christiane.safi@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 13
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Liliane',
            'last_name' => 'Safi',
            'email' => 'liliane.safi@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 13
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Hassane',
            'last_name' => 'Toubia',
            'email' => 'hassan.toubia@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 13
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Antonios',
            'last_name' => 'Younes',
            'email' => 'tony.younes@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 13
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Sud

        $employee = Employee::create([
            'first_name' => 'Sophie',
            'last_name' => 'Jarjat',
            'email' => 'sophie.jarjat@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 14
        ]);
        $role = Role::findByName('employee');
        $employee->is_supervisor = true;
        $employee->can_submit_requests = false;
        $employee->save();
        $employee->roles()->save($role);
        $department = Department::find(14);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => 'Aida',
            'last_name' => 'Ajami',
            'email' => 'aida.ezzedine@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 14
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Hanane',
            'last_name' => 'Jabbour',
            'email' => 'hanane.jabbour@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 14
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Mona',
            'last_name' => 'Sabbah',
            'email' => 'mona.sabbah@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 14
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Youssef',
            'last_name' => 'Takach',
            'email' => 'youssef.takach@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 14
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Tripoli

        $employee = Employee::create([
            'first_name' => 'Emmanuel',
            'last_name' => 'Khoury',
            'email' => 'emmanuel.khoury@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 15
        ]);
        $role = Role::findByName('employee');
        $employee->is_supervisor = true;
        $employee->can_submit_requests = false;
        $employee->save();
        $employee->roles()->save($role);
        $department = Department::find(15);
        $department['manager_id'] = $employee['id'];
        $department->save();

        $employee = Employee::create([
            'first_name' => 'Georges',
            'last_name' => 'Mehrez',
            'email' => 'georges.mehrez@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 15
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Maribel',
            'last_name' => 'Moussi',
            'email' => 'maribel.moussi@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 15
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Nicolas',
            'last_name' => 'Mansour',
            'email' => 'nicolas.mansour@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 15
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        $employee = Employee::create([
            'first_name' => 'Nada',
            'last_name' => 'Dennaoui',
            'email' => 'nada.dennaoui@if-liban.com',
            'password' => Hash::make('123456'),
            'department_id' => 15
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);
    }
}
