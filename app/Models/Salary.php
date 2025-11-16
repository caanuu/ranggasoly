<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'present_rate', 'sick_rate', 'absent_rate'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
