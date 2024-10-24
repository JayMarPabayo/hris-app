<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'maxCredits',
        'maxDays',
        'eomVoting',
    ];


    public function getRemainingCreditsForEmployee($userId)
    {
        $totalLeaveRequests = LeaveRequest::where('user_id', $userId)
            ->where('status', '!=', 'rejected')
            ->count();

        return $this->maxCredits - $totalLeaveRequests;
    }

    public function isVotingOpen()
    {
        return $this->eomVoting;
    }
}
