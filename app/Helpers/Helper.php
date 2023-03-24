<?php

namespace App\Helpers;

use App\Models\Confessionnel;
use App\Models\Holiday;
use Carbon\CarbonPeriod;
use Spatie\Permission\Models\Role;

class Helper
{
    public function checkIfNormalEmployee($user) {
        return ($user->hasExactRoles('employee') && $user->is_supervisor == false);
    }

    public function getHolidays()
    {
        $holidays = Holiday::all();
        $holiday_dates = [];
        foreach ($holidays as $holiday) {
            $period = CarbonPeriod::create($holiday->from, $holiday->to);
            foreach ($period as $date) {
                if (!in_array($date->toDateString(), $holiday_dates))
                    $holiday_dates[] = $date->toDateString();
            }
        }
        return $holiday_dates;
    }

    public function getConfessionnels()
    {
        $confessionnels = Confessionnel::all();
        $confessionnel_dates = [];
        foreach ($confessionnels as $confessionnel) {
            if (!in_array($confessionnel->date, $confessionnel_dates))
                $confessionnel_dates[] = $confessionnel->date;
        }
        return $confessionnel_dates;
    }


    public function isHoliday($date) {
        $holidays = $this->getHolidays();
        return (in_array($date, $holidays));
    }

    public function isWeekend($date, $employee)
    {
        return in_array(date('N', strtotime($date)), $employee->weekdays_off);
    }

    public function getRoleIds() {
        $roles_ids = [];
        $roles = Role::all();
        foreach ($roles as $role) {
            $roles_ids [] = $role->id;
        }
        return $roles_ids;
    }
}
