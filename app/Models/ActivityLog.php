<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'employee_id', 'activity'
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}