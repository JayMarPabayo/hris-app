<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'day',
        'schedule_id',
        'start_time',
        'end_time',
    ];

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
}
