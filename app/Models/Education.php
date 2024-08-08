<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'school',
        'degree',
        'start',
        'end',
        'earned',
        'graduated',
        'accolades',
        'employee_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
