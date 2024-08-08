<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Children extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'gender',
        'birthdate',
        'employee_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
