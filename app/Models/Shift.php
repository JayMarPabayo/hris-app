<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Shift extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'weekdays',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'weekdays' => 'array',
    ];

    public static array $shiftnames = ['Morning Shift', 'Night Shift', 'Afternoon Shift', 'Dawn Shift'];
    public static array $weekdays = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday'
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shift) {
            if (empty($shift->slug)) {
                $shift->slug = Str::slug($shift->name);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
