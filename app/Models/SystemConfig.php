<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'maxCredits',
        'evaluation',
    ];


    public function getRemainingCreditsForEmployee($userId)
    {
        $totalLeaveDays = LeaveRequest::where('user_id', $userId)
            ->where('status', 'approved')
            ->get()
            ->sum(function ($leaveRequest) {
                return $leaveRequest->start->diffInDays($leaveRequest->end) + 1;
            });

        return $this->maxCredits - $totalLeaveDays;
    }

    public function isMonthlyEvaluationOpen()
    {
        return $this->evaluation;
    }
}
