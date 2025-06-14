<?php

namespace Database\Seeders;

use App\Models\LeaveConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LeaveConfig::create(['key' => 'year', 'value' => 2025]);
        LeaveConfig::create(['key' => 'start_day', 'value' => 1]);
        LeaveConfig::create(['key' => 'start_month', 'value' => 1]);
        LeaveConfig::create(['key' => 'expire_day', 'value' => 31]);
        LeaveConfig::create(['key' => 'expire_month', 'value' => 5]);
    }
}
