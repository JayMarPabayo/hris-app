<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Evaluation extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'coworker_id',
        'question_id',
        'rating',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function question(): HasOne
    {
        return $this->hasOne(Question::class);
    }
}
