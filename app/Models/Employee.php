<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'picture',
        'firstname',
        'middlename',
        'lastname',
        'nameextension',
        'designation',
        'birthdate',
        'birthplace',
        'gender',
        'citizenship',
        'civilstatus',
        'residential_houseblock',
        'residential_street',
        'residential_subdivision',
        'residential_barangay',
        'residential_city',
        'residential_province',
        'residential_region',
        'residential_zipcode',
        'permanent_houseblock',
        'permanent_street',
        'permanent_subdivision',
        'permanent_barangay',
        'permanent_city',
        'permanent_province',
        'permanent_region',
        'permanent_zipcode',
        'height',
        'weight',
        'bloodtype',
        'pagibig',
        'philhealth',
        'sss',
        'tin',
        'agencynumber',
        'telephone',
        'mobile',
        'email',
        'spouse_firstname',
        'spouse_middlename',
        'spouse_lastname',
        'spouse_nameextension',
        'spouse_occupation',
        'spouse_employerbusiness',
        'spouse_businessaddress',
        'spouse_telephone',
        'father_firstname',
        'father_middlename',
        'father_lastname',
        'father_nameextension',
        'mother_firstname',
        'mother_middlename',
        'mother_lastname',
        'mother_nameextension',
        'department_id',
    ];

    public static array $gender = ['Male', 'Female'];
    public static array $civilstatus = ['Single', 'Married', 'Divorced', 'Widowed'];
    public static array $bloodtype = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
    public static array $suffixes = ['None', 'Sr.', 'Jr.', 'III', 'IV', 'V'];
    public static array $citizenships = ['Filipino', 'Japanese', 'American', 'Chinese', 'Korean'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Children::class);
    }

    public function education(): HasMany
    {
        return $this->hasMany(Education::class);
    }

    public function eligibilities(): HasMany
    {
        return $this->hasMany(Eligibilities::class);
    }

    public function workexperiences(): HasMany
    {
        return $this->hasMany(WorkExperience::class);
    }
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function ($query) use ($keyword) {
            $query->where('firstname', 'like', '%' . $keyword . '%')
                ->orWhere('middlename', 'like', '%' . $keyword . '%')
                ->orWhere('lastname', 'like', '%' . $keyword . '%')
                ->orWhere('designation', 'like', '%' . $keyword . '%')
                ->orWhereHas('department', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                });
        });
    }

    public function scopeByDepartment(Builder $query, string $department): Builder
    {
        return $query->where('department_id', '=', $department);
    }

    public function scopeByDesignation(Builder $query, string $designation): Builder
    {
        return $query->where('designation', '=', $designation);
    }

    public function leaveRequests()
    {
        // Retrieve the user associated with this employee
        $user = User::where('employee_id', $this->id)->first();

        if ($user) {
            // Sum the days of approved leave requests
            return LeaveRequest::where('user_id', $user->id)
                ->where('status', 'approved')
                ->get()
                ->sum(function ($leaveRequest) {
                    return $leaveRequest->start->diffInDays($leaveRequest->end) + 1; // +1 to include both start and end dates
                });
        }

        return 0; // No leave days if no user or approved leave requests
    }


    public function getRemainingCredits()
    {
        $config = SystemConfig::first();
        $maxCredits = $config->maxCredits;

        // Get the total leave days instead of the count
        $totalLeaveDays = $this->leaveRequests();

        return $maxCredits - $totalLeaveDays;
    }

    public function leaveRequestDates()
    {
        $userId = $this->user->id ?? User::where('employee_id', $this->id)->value('id');

        if (!$userId) {
            return collect([]);
        }

        $leaveRequests = LeaveRequest::where('user_id', $userId)
            ->where('status', 'approved')
            ->get(['start', 'end']);

        $dates = [];

        foreach ($leaveRequests as $leave) {
            $period = CarbonPeriod::create($leave->start, $leave->end);

            foreach ($period as $date) {
                $dates[] = $date->format('Y-m-d');
            }
        }

        return collect($dates)->unique()->values();
    }
}
