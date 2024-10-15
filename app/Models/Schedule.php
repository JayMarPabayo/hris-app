<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_id',
        'employee_id',
        'dayoffs'
    ];

    protected $casts = [
        'dayoffs' => 'array',
    ];


    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($schedule) {
            if (empty($schedule->dayoffs)) {
                $schedule->dayoffs = [];
            }
        });
    }

    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function ($query) use ($keyword) {
            $query->orWhereHas('employee', function ($query) use ($keyword) {
                $query->where('firstname', 'like', '%' . $keyword . '%')
                    ->orWhere('middlename', 'like', '%' . $keyword . '%')
                    ->orWhere('lastname', 'like', '%' . $keyword . '%')
                    ->orWhere('designation', 'like', '%' . $keyword . '%')
                    ->orWhereHas('department', function ($query) use ($keyword) {
                        $query->where('name', 'like', '%' . $keyword . '%');
                    });
            });
        });
    }
}
