<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ShiftController;

use Illuminate\Http\Request as RequestRequest;
use App\Http\Requests\LeaveRequestRequest;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Employee;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\Shift;
use App\Models\Schedule;
use App\Models\SystemConfig;
use App\Models\User;
use App\Models\Voting;
use Illuminate\Validation\Rule;

// -- GUEST

Route::middleware('guest')->group(function () {

    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('login', fn() => to_route('auth.login'))->name('login');

    Route::get('auth/login', function () {
        return view('auth.login');
    })->name('auth.login');
    Route::post('auth/login', function (Illuminate\Http\Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only(['username', 'password']);
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user(); // Get the authenticated user

            // Check the user's role and redirect accordingly
            /** @disregard [OPTIONAL_CODE] [OPTION_DESCRIPTION] */
            if ($user->hasRole('Employee')) {
                return redirect()->route('profile.index');
            }
            /** @disregard [OPTIONAL_CODE] [OPTION_DESCRIPTION] */
            if ($user->hasRole('Administrator')) {
                return redirect()->route('employees.index');
            }

            // Default redirect if no role is matched
            return redirect()->intended('/');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    })->name('auth.login');
});

// -- USERS
Route::middleware('auth')->group(function () {

    // For Employee
    Route::middleware('role:Employee')->group(function () {

        Route::get('profile', function () {
            $employeeId = Auth::user()->employee_id;
            $employee = Employee::findOrFail($employeeId);
            $schedule = Schedule::where('employee_id', $employee->id)->first();
            $systemConfig = SystemConfig::first();
            $isVotingOpen = $systemConfig ? $systemConfig->isVotingOpen() : false;

            $userVoted = Voting::where('month', date('Y-m'))
                ->where('user_id', Auth::user()->id)
                ->first();

            return view('profile.index', [
                'employee' => $employee,
                'schedule' => $schedule,
                'userVoted' => !!$userVoted,
                'weekdays' => Shift::$weekdays,
                'isVotingOpen' => $isVotingOpen
            ]);
        })->name('profile.index');

        Route::get('profile/leave-request', function () {

            $userId = Auth::user()->id;
            $leaveRequests = LeaveRequest::where('user_id', $userId)->get();

            $config = SystemConfig::first();
            $remainingCredits = $config->getRemainingCreditsForEmployee($userId);
            return view('profile.leave', [
                'leaveRequests' => $leaveRequests,
                'remainingCredits' => $remainingCredits
            ]);
        })->name('profile.leave');

        Route::post('profile/leave-request', function (LeaveRequestRequest $request) {
            $userId = Auth::user()->id;
            $config = SystemConfig::first();
            $remainingCredits = $config->getRemainingCreditsForEmployee($userId);

            if ($remainingCredits <= 0) {
                return redirect()->route('profile.leave')
                    ->with('error', "Not enough leave credits.");
            }

            LeaveRequest::create($request->validated());

            return redirect()->route('profile.leave')->with('success', 'Leave request submitted successfully.');
        })->name('profile.leave');

        Route::resource('employee-of-the-month', VotingController::class)->only(['create', 'store']);
    });

    // For Administrator
    Route::middleware('role:Administrator')->group(function () {
        Route::get('/', function () {
            return redirect()->route('employees.index');
        });

        Route::resource('employees', EmployeeController::class);
        Route::resource('administration/departments', DepartmentController::class);
        Route::resource('administration/shifts', ShiftController::class);
        Route::resource('schedules', ScheduleController::class);
        Route::resource('employee-of-the-month', VotingController::class)->only(['index']);

        Route::get('monthly-employee-of-the-month', function (Illuminate\Http\Request $request) {
            $year = $request->input('year') ?? date('Y');

            if (!preg_match('/^\d{4}$/', $year) || (int)$year < 1900 || (int)$year > date('Y')) {
                return redirect()->route('employee-of-the-month.monthly')->with('error', 'Invalid Year');
            }

            $yearlyEOM = [];

            for ($month = 1; $month <= 12; $month++) {

                $formattedMonth = str_pad($month, 2, '0', STR_PAD_LEFT);
                $yearMonth = "{$year}-{$formattedMonth}";

                $topEmployee = Employee::byMonth($yearMonth)
                    ->orderBy('total_votes', 'desc')
                    ->first();

                if ($topEmployee && $topEmployee->total_votes > 0) {
                    $yearlyEOM[$yearMonth] = $topEmployee;
                } else {
                    $yearlyEOM[$yearMonth] = null;
                }
            }

            return view('eom-results.monthly', ['yearlyEOM' => $yearlyEOM, 'currentYear' => $year]);
        })->name('employee-of-the-month.monthly');

        Route::get('reports', function () {
            return view('reports.index');
        })->name('reports.index');

        Route::get('export', function (Illuminate\Http\Request $request) {
            $employeeId = $request->query('employee');
            $departmentId = $request->query('department');
            $designation = $request->query('designation');

            $data = [
                'employee' => null,
                'department' => null,
                'employees' => null,
                'designation' => $designation,
            ];

            if ($employeeId) {
                $data['employee'] = Employee::findOrFail($employeeId);
            }

            if ($departmentId) {
                $department = Department::findOrFail($departmentId);
                $data['department'] = $department;

                if ($designation) {
                    $data['employees'] = Employee::where('department_id', $departmentId)
                        ->where('designation', $designation)
                        ->get();
                } else {
                    $data['employees'] = $department->employees;
                }
            }

            return view('reports.export', ['data' => $data]);
        })->name('reports.export');

        Route::get('export/{shift:slug}', function ($slug) {
            $shift = null;
            $schedules = [];

            if ($slug === 'all-shifts') {
                $schedules = Schedule::all();
            } else {
                $shift = Shift::where('slug', $slug)->firstOrFail();
                $schedules = $shift->schedules;
            }

            return view('reports.schedules', ['shift' => $shift, 'schedules' => $schedules]);
        })->name('reports.schedules');

        Route::get('administration/departments', function () {
            return view('administration.index', ['departments' => Department::withCount('employees')->get()]);
        })->name('administration.index');

        Route::get('administration/leave-request', function () {
            $config = SystemConfig::first();
            return view('administration.leave-request', ['config' => $config]);
        })->name('administration.leave-request.index');

        Route::get('administration/eom-voting', function () {
            $config = SystemConfig::first();
            return view('administration.eom-voting', ['config' => $config]);
        })->name('administration.eom-voting');

        Route::put('administration/leave-request/max-credits', [LeaveRequestController::class, 'updateMaxCredits'])->name('leave-request.updateMaxCredits');
        Route::put('administration/leave-request/max-days', [LeaveRequestController::class, 'updateMaxDays'])->name('leave-request.updateMaxDays');
        Route::put('administration/eom-voting', [VotingController::class, 'updateEOMVoting'])->name('eom-voting.updateVoting');


        Route::get('leave-requests', function () {
            $leaveRequests = LeaveRequest::all();
            return view('requests.index', ['leaveRequests' => $leaveRequests]);
        })->name('requests.index');

        Route::delete('leave-requests/{request}', function (LeaveRequest $request) {
            $request->update(['status' => 'rejected']);
            return redirect()->route('requests.index')->with('success', 'Leave request rejected successfully.');
        })->name('requests.destroy');

        Route::put('leave-requests/{request}', function (LeaveRequest $request) {
            $request->update(['status' => 'approved']);
            return redirect()->route('requests.index')->with('success', 'Leave request approved successfully.');
        })->name('requests.update');
    });

    Route::delete('logout', fn() => to_route('auth.logout'))->name('logout');

    Route::put('auth/login/{user}', function (Illuminate\Http\Request $request, User $user) {

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable',
            'current_password' => 'required_with:password',
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            $user->password = Hash::make($validatedData['password']);
        }

        $user->name = $validatedData['name'];
        $user->username = $validatedData['username'];

        $user->save();

        return redirect()->back()->with('success', 'Account Updated.');
    })->name('auth.update');

    Route::delete('auth/logout', function () {
        Auth::logout();
        Request::session()->invalidate();
        Request::session()->regenerateToken();
        return redirect('/');
    })->name('auth.logout');
});
