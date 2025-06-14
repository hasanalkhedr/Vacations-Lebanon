<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveConfig extends Model
{
    use HasFactory;
    protected $primaryKey = 'key'; // Specify primary key column

    protected $keyType = 'string';

    public $incrementing = false;
    protected $fillable = [
        'key',
        'value'
    ];
}
