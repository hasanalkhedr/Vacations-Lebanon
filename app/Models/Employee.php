<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use MBarlow\Megaphone\HasMegaphone;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, HasMegaphone;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'nb_of_days',
        'confessionnels',
        'department_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //
    ];

    protected $attributes = [
        'nb_of_days' => 30,
        'confessionnels' => 3,
    ];

    public function scopeSearch($query, array $filters)
    {
        if ($filters['search'] ?? false) {
            $query->where('first_name', 'like', '%' . request('search') . '%')
                ->orwhere('last_name', 'like', '%' . request('search') . '%')
                ->orwhereHas('roles', function ($q) {
                    $q->where('name', 'like', '%' . request('search') . '%');
                })
                ->orwhereHas('department', function ($q) {
                    $q->where('name', 'like', '%' . request('search') . '%');
                });
        };
    }


    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function leaves() {
        return $this->hasMany(Leave::class);
    }

    public function overtimes() {
        return $this->hasMany(Overtime::class);
    }
}
