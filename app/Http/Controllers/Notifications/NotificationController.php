<?php

namespace App\Http\Controllers\Notifications;

use App\Models\Employee;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Hexadecimal;

class NotificationController
{

    public function store(Request $request) {

        $users = Employee::all()->except(auth()->user()->id);

        $notification = new \MBarlow\Megaphone\Types\General(
            $request->title, // Notification Title
            $request->body, // Notification Body
        );

        foreach ($users as $user) {
            $user->notify($notification);
        }

        session()->flash('megaphone_success', __('Notifications sent successfully!'));

        return redirect()->route('employees.sendMessage');
    }
}
