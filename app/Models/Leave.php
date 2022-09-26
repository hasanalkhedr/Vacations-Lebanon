<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Leave extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'leave_duration_id',
        'from',
        'to',
        'travelling',
        'leave_type_id',
        'attachment_path',
        'date_of_submission',
        'substitute_employee_id	',
        'leave_status',
        'processing_office_role',
        'cancellation_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //
    ];

    public function scopeSearch($query, array $filters)
    {
        if ($filters['search'] ?? false) {
            $query->whereHas('employee', function ($q) {
                    $q->where('first_name', 'like', '%' . request('search') . '%')
                        ->orwhere('last_name', 'like', '%' . request('search') . '%');
                });;
        };
    }

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function leave_type() {
        return $this->belongsTo(LeaveType::class);
    }

    public function leave_duration() {
        return $this->belongsTo(LeaveDuration::class);
    }

    public function substitute_employee() {
        return $this->belongsTo(Employee::class);
    }

    public function processing_officer() {
        return $this->belongsTo(Role::class, 'processing_officer_role');
    }
}
