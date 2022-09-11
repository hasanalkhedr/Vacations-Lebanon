<?php

namespace App\Services;

use App\Jobs\SendLeaveRequestEmailJob;

class LeaveService
{
    public function sendEmailToProcessingOfficers($processing_officers) {
        foreach ($processing_officers as $processing_officer) {
            dispatch(new SendLeaveRequestEmailJob($processing_officer));
        }
    }
}
