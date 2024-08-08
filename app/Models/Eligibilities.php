<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eligibilities extends Model
{
    use HasFactory;

    protected $fillable = [
        'examination',
        'rating',
        'examdate',
        'address',
        'license',
        'validity',
        'employee_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
