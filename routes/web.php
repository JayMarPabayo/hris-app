<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ShiftController;

use App\Models\SwapRequest;
use Illuminate\Http\Request as RequestRequest;
use App\Http\Requests\LeaveRequestRequest;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Employee;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\NegativeVoting;
use App\Models\Shift;
use App\Models\Schedule;
use App\Models\SystemConfig;
use App\Models\User;
use App\Models\Voting;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

use Carbon\Carbon;

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

    // -- FORGOT PASSWORD -- 
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::post('/forgot-password', function (RequestRequest $request) {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    })->name('password.email');

    Route::get('/reset-password/{token}', function (string $token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');

    Route::post('/reset-password', function (RequestRequest $request) {
        $credentials = $request->validate([
            'token' => 'required',
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    })->name('password.update');
});

// -- USERS
Route::middleware('auth')->group(function () {

    // For Employee
    Route::middleware('role:Employee')->group(function () {

        Route::get('profile', function () {
            $employeeId = Auth::user()->employee_id;
            $employee = Employee::findOrFail($employeeId);
            $schedules = Schedule::where('employee_id', $employee->id)->get();
            $systemConfig = SystemConfig::first();
            $isVotingOpen = $systemConfig ? $systemConfig->isVotingOpen() : false;

            return view('profile.index', [
                'employee' => $employee,
                'schedules' => $schedules,
                'weekdays' => Shift::$weekdays,
                'isVotingOpen' => $isVotingOpen,
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

            if (!$config) {
                return redirect()->route('profile.leave')
                    ->with('error', "System configuration is missing.");
            }


            $remainingCredits = $config->getRemainingCreditsForEmployee($userId);

            $startDate = Carbon::parse($request->input('start'));
            $endDate = Carbon::parse($request->input('end'));
            $daysRequested = $startDate->diffInDays($endDate) + 1;


            if (($remainingCredits - $daysRequested) < 0) {
                return redirect()->route('profile.leave')
                    ->with('error', "Not enough leave credits.");
            }

            LeaveRequest::create($request->validated());

            return redirect()->route('profile.leave')->with('success', 'Leave request submitted successfully.');
        })->name('profile.leave');

        Route::get('profile/swap-request', function (RequestRequest $request) {
            $departmentId = Auth::user()->employee->department_id;
            $employeeId = Auth::user()->employee->id;

            $employee = $request->input('employee_id');
            $week = $request->input('week');

            $employees = Employee::where('department_id', $departmentId)->whereNot('id',  $employeeId)->get();
            $schedule = Schedule::where('employee_id', $employee)->where('week', $week)->first();

            return view('schedules.request-swap', [
                'employees' => $employees,
                'employee_id' => $employee,
                'weekdays' => Shift::$weekdays,
                'week' => $week,
                'schedule' => $schedule
            ]);
        })->name('profile.swap-request');

        Route::post('profile/swap-request', function (RequestRequest $request) {

            $request->validate([
                'employee' => 'required|exists:employees,id',
                'week' => 'required|string',
            ]);

            $requesterId = Auth::user()->employee->id;

            SwapRequest::create([
                'employee_id' => $requesterId,
                'coworker_id' => $request->input('employee'),
                'week' => $request->input('week'),
                'status' => 'pending',
            ]);

            return redirect()->route('profile.swap-request')->with('success', 'Your swap request has been submitted!');
        })->name('profile.swap-post');

        Route::resource('employee-of-the-month', VotingController::class)->only(['create', 'store']);

        Route::post('negative-employee-of-the-month', function (RequestRequest $request) {
            $validatedData = $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'month' => 'required|date_format:Y-m',
                'remarks' => 'nullable|string',
            ]);

            $validatedData['user_id'] = Auth::user()->id;

            $existingVote = NegativeVoting::where('month', $validatedData['month'])
                ->where('user_id', Auth::user()->id)
                ->first();

            $systemConfig = SystemConfig::first();
            $isVotingOpen = $systemConfig ? $systemConfig->isVotingOpen() : false;

            if ($existingVote) {
                abort(403, 'You have already voted for this month.');
            }

            if (!$isVotingOpen) {
                abort(403, 'Voting is still not open.');
            }

            NegativeVoting::create($validatedData);

            return redirect()->route('profile.index')->with('success', 'Your vote has been submitted!');
        })->name('negative.voting');
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
        Route::resource('evaluations', VotingController::class)->only(['index']);

        Route::get('monthly-evaluations', function (Illuminate\Http\Request $request) {
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

            $negativeYearlyEOM = [];

            for ($month = 1; $month <= 12; $month++) {

                $formattedMonth = str_pad($month, 2, '0', STR_PAD_LEFT);
                $yearMonth = "{$year}-{$formattedMonth}";

                $topEmployee = Employee::byNegativeMonth($yearMonth)
                    ->orderBy('total_votes', 'desc')
                    ->first();

                if ($topEmployee && $topEmployee->total_votes > 0) {
                    $negativeYearlyEOM[$yearMonth] = $topEmployee;
                } else {
                    $negativeYearlyEOM[$yearMonth] = null;
                }
            }

            return view('eom-results.monthly', ['yearlyEOM' => $yearlyEOM, 'negativeYearlyEOM' => $negativeYearlyEOM, 'currentYear' => $year]);
        })->name('evaluations.monthly');

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
        Route::put('administration/eom-voting', [VotingController::class, 'updateEOMVoting'])->name('eom-voting.updateVoting');

        Route::get('schedule-swap-requests', function () {
            $swapRequests = SwapRequest::all();
            return view('schedules.swap-requests', ['swapRequests' => $swapRequests]);
        })->name('schedules.swap.requests');

        Route::delete('schedule-swap-requests/{request}', function (SwapRequest $request) {
            $request->update(['status' => 'rejected']);
            return redirect()->route('schedules.swap.requests')->with('success', 'Swap Request Rejected.');
        })->name('schedules.swap.reject');

        Route::put('schedule-swap-requests/{request}', function (SwapRequest $request) {

            $requesterSchedule = $request->getSchedule();
            $coWorkerSchedule = $request->getCoworkerSchedule();

            if ($requesterSchedule && $coWorkerSchedule) {
                $requesterEmployeeId = $requesterSchedule->employee_id;
                $requesterSchedule->update(['employee_id' => $request->coworker_id]);
                $coWorkerSchedule->update(['employee_id' => $requesterEmployeeId]);
            }

            $request->update(['status' => 'approved']);
            return redirect()->route('schedules.swap.requests')->with('success', 'Swap Request Approved.');
        })->name('schedules.swap.approved');

        Route::get('leave-requests', function () {
            $leaveRequests = LeaveRequest::all();
            return view('requests.index', ['leaveRequests' => $leaveRequests]);
        })->name('requests.index');

        Route::delete('leave-requests/{request}', function (LeaveRequest $request) {
            $request->update(['status' => 'rejected']);
            return redirect()->route('requests.index')->with('success', 'Leave request rejected successfully.');
        })->name('requests.destroy');

        Route::put('leave-requests/{request}', function (LeaveRequest $request) {

            $userId = $request->user_id;
            $config = SystemConfig::first();

            if (!$config) {
                return redirect()->route('profile.leave')
                    ->with('error', "System configuration is missing.");
            }

            $startDate = Carbon::parse($request->start);
            $endDate = Carbon::parse($request->end);
            $daysRequested = $startDate->diffInDays($endDate) + 1;

            $remainingCredits = $config->getRemainingCreditsForEmployee($userId);

            if (($remainingCredits - $daysRequested) < 0) {
                return redirect()->route('requests.index')
                    ->with('error', "Not enough leave credits for the requested period.");
            }

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
