<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SwapRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'coworker_id',
        'week',
        'status',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public static function hasAnyPendingRequest(): bool
    {
        return self::where('status', 'pending')->exists();
    }
    public function getSchedule()
    {
        $schedule = Schedule::where('employee_id', $this->employee_id)
            ->where('week', $this->week)
            ->with('shift')
            ->first();

        return $schedule;
    }

    public function getCoworkerFullname(): ?string
    {
        $employee = Employee::where('id', $this->coworker_id)->first();

        if (!$employee) {
            return null;
        }

        return "{$employee->lastname}, {$employee->firstname} " . strtoupper(substr($employee->middlename, 0, 1)) . ".";
    }

    public function getCoworkerSchedule()
    {
        $schedule = Schedule::where('employee_id', $this->coworker_id)
            ->where('week', $this->week)
            ->with('shift')
            ->first();

        return $schedule;
    }
}
