<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EvaluationController;
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
use App\Models\Evaluation;
use App\Models\LeaveRequest;
use App\Models\NegativeVoting;
use App\Models\Question;
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
            $isMonthlyEvaluationOpen = $systemConfig ? $systemConfig->isMonthlyEvaluationOpen() : false;

            return view('profile.index', [
                'employee' => $employee,
                'schedules' => $schedules,
                'weekdays' => Shift::$weekdays,
                'isMonthlyEvaluationOpen' => $isMonthlyEvaluationOpen,
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

            $pendingLeaveRequest = LeaveRequest::where('user_id', $userId)
                ->where('status', 'pending')
                ->exists();

            if ($pendingLeaveRequest) {
                return redirect()->route('profile.leave')
                    ->with('error', "You still have a pending leave request.");
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
            $week = $request->input('week') ?? date('Y-\WW');

            $employeeSchedule = Schedule::where('week', $week)
                ->where('employee_id', $employeeId)
                ->first();

            if (!$employeeSchedule) {
                $weekNumber = date('W', strtotime($week . '-1'));
                return redirect()->back()->with('error', "You have no week {$weekNumber} schedule to swap with.");
            }

            $iRequestedForThisWeek = SwapRequest::where('employee_id', $employeeId)
                ->where('week', $week)
                ->exists();

            $schedules = Schedule::where('week', $week)
                ->whereHas('employee', function ($query) use ($departmentId) {
                    $query->where('department_id', $departmentId);
                })
                ->whereNot('employee_id', $employeeId)
                ->with('employee')
                ->get()
                ->sortBy(function ($schedule) {
                    return $schedule->id;
                })
                ->map(function ($schedule) use ($employeeId, $week) {
                    $schedule->isRequestedByThisEmployee = SwapRequest::where('employee_id', $employeeId)
                        ->where('coworker_id', $schedule->employee->id)
                        ->where('week', $week)
                        ->exists();

                    return $schedule;
                });

            return view('schedules.request-swap', [
                'employee_id' => $employee,
                'employee' => Auth::user()->employee,
                'weekdays' => Shift::$weekdays,
                'week' => $week,
                'schedules' => $schedules,
                'iRequestedForThisWeek' => $iRequestedForThisWeek
            ]);
        })->name('profile.swap-request');

        Route::post('profile/swap-request', function (RequestRequest $request) {

            $request->validate([
                'employee' => 'required|exists:employees,id',
                'week' => 'required|string',
            ]);

            $requesterId = Auth::user()->employee->id;

            $exists = SwapRequest::where('employee_id', $requesterId)
                ->where('week', $request->input('week'))
                ->exists();

            if ($exists) {
                return redirect()->route('profile.swap-request', [
                    'week' => $request->input('week'),
                ])->withErrors(['error' => 'You have already submitted a swap request for this week.']);
            }

            SwapRequest::create([
                'employee_id' => $requesterId,
                'coworker_id' => $request->input('employee'),
                'week' => $request->input('week'),
                'status' => 'pending',
            ]);

            return redirect()->route('profile.swap-request', [
                'week' => $request->input('week')
            ])->with('success', 'Your swap request has been submitted!');
        })->name('profile.swap-post');

        Route::get('profile/evaluation', function () {
            $employee = Auth::user()->employee;
            $departmentId = $employee->department_id;

            $evaluatedCoworkerIds = Evaluation::where('employee_id', $employee->id)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->pluck('coworker_id');

            $evaluatedCoworkers = Employee::whereIn('id', $evaluatedCoworkerIds)->get();

            // Add 'avg_rating' property to each evaluated coworker
            $evaluatedCoworkers->each(function ($coworker) use ($employee) {
                $coworker->avg_rating = Evaluation::where('employee_id', $employee->id)
                    ->where('coworker_id', $coworker->id)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->avg('rating');
            });

            $coworker = Employee::where('department_id', $departmentId)
                ->whereNot('id', $employee->id)
                ->whereNotIn('id', $evaluatedCoworkerIds)
                ->first();

            $questions = Question::all();


            return view('profile.evaluation', [
                'employee' => $employee,
                'coworker' => $coworker,
                'questions' => $questions,
                'evaluatedCoworkers' => $evaluatedCoworkers,
            ]);
        })->name('profile.evaluation');

        Route::post('profile/evaluation', function (RequestRequest $request) {
            $employeeId = Auth::user()->employee->id;

            $request->validate([
                'coworker' => 'required|exists:employees,id',
                'question' => 'required|array',
                'rating' => 'required|array',
                'rating.*' => 'required|integer|between:1,5',
            ]);

            $evaluations = [];
            foreach ($request->question as $index => $questionId) {
                $evaluations[] = [
                    'employee_id' => $employeeId,
                    'coworker_id' => $request->coworker,
                    'question_id' => $questionId,
                    'rating' => $request->rating[$index],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Evaluation::insert($evaluations);

            return redirect()->route('profile.evaluation')->with('success', 'Evaluation submitted successfully!');
        })->name('profile.evaluation.post');
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
        Route::resource('evaluations', EvaluationController::class)->only(['index']);

        Route::get('monthly-evaluations', function (RequestRequest $request) {
            $year = $request->input('year') ?? date('Y');

            if (!preg_match('/^\d{4}$/', $year) || (int)$year < 1900 || (int)$year > date('Y')) {
                return redirect()->route('evaluations.monthly')->with('error', 'Invalid Year');
            }

            $yearlyEOM = [];

            for ($month = 1; $month <= 12; $month++) {
                $evaluations = Evaluation::whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->get()
                    ->groupBy('coworker_id');

                $employeeRatings = $evaluations->map(function ($evaluationGroup) {
                    return $evaluationGroup->avg('rating');
                });

                if ($employeeRatings->isNotEmpty()) {
                    $bestEmployeeId = $employeeRatings->keys()->first(function ($id) use ($employeeRatings) {
                        return $employeeRatings[$id] === $employeeRatings->max();
                    });

                    $bestEmployee = Employee::find($bestEmployeeId);

                    $yearlyEOM["$year-" . str_pad($month, 2, '0', STR_PAD_LEFT)] = $bestEmployee;
                }
            }

            return view('evaluation.monthly', [
                'yearlyEOM' => $yearlyEOM,
                'currentYear' => $year,
            ]);
        })->name('evaluations.monthly');

        Route::get('reports', function () {

            $employees = Employee::paginate(15);

            return view('reports.index', ['employees' => $employees]);
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

        Route::get('records', function (Illuminate\Http\Request $request) {
            $employeeId = $request->query('employee');

            $user = User::where('employee_id', $employeeId)->first();

            $userId = $user->id;

            $leaveRequests = LeaveRequest::where('user_id', $userId)
                ->where(function ($query) {
                    $query->where('status', 'approved')
                        ->orWhere('status', 'rejected');
                })
                ->get();

            $schedules = Schedule::where('employee_id', $employeeId)
                ->get();

            $employee = Employee::findOrFail($employeeId);

            return view('reports.records', ['employee' => $employee, 'leaveRequests' => $leaveRequests, 'schedules' => $schedules, 'weekdays' => Shift::$weekdays,]);
        })->name('reports.records');

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

        Route::get('administration/evaluation', function () {
            $config = SystemConfig::first();
            return view('administration.evaluation', ['config' => $config]);
        })->name('administration.evaluation');

        Route::put('administration/leave-request/max-credits', [LeaveRequestController::class, 'updateMaxCredits'])->name('leave-request.updateMaxCredits');
        Route::put('administration/evaluation/update', [EvaluationController::class, 'updateEvaluation'])->name('evaluation.update');

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
