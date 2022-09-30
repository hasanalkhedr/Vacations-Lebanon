<?php

namespace App\Helpers;

class Helper
{
    public function checkIfNormalEmployee($user) {
        if(!$user->hasAnyRole(['human_resource', 'sg'])) {
            if(!$user->is_supervisor) {
                return true;
            }
        }
        return false;
    }
}
