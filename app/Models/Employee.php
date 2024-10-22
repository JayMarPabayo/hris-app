<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'gsis',
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

    public function scopeByMonth(Builder $query, string $month): Builder
    {
        // Extract the year and month
        [$year, $month] = explode('-', $month);

        // Calculate the first day of the given month
        $firstDayOfMonth = Carbon::createFromDate($year, $month, 1);


        $lastDayOfMonth = $firstDayOfMonth->copy()->endOfMonth();

        // Get the ISO week format for the start and end of the month
        $startWeek = $firstDayOfMonth->isoFormat('YYYY-[W]WW');
        $endWeek = $lastDayOfMonth->isoFormat('YYYY-[W]WW');

        // Filter the evaluations within the start and end weeks
        return $query->whereHas('evaluations', function ($query) use ($startWeek, $endWeek) {
            $query->whereBetween('week', [$startWeek, $endWeek]);
        })->withAvg('evaluations', 'rating');
    }

    public function leaveRequests()
    {
        $user = User::where('employee_id', $this->id)->first();

        if ($user) {
            return LeaveRequest::where('user_id', $user->id)->where('status', '!=', 'rejected')->count();
        }

        return 0;
    }


    public function getRemainingCredits()
    {
        $config = SystemConfig::first();
        $maxCredits = $config->maxCredits;

        $totalLeaveDays = $this->leaveRequests();

        return $maxCredits - $totalLeaveDays;
    }
}
