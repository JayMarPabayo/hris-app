<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reason',
        'custom_reason',
        'start',
        'end',
        'status',
    ];

    protected $casts = [
        'start' => 'date',
        'end' => 'date',
    ];


    public function user()
    {
        return $this->belongsTo(related: User::class);
    }
}
