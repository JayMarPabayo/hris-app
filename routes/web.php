<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EvaluationController;
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
use App\Models\User;

use Illuminate\Validation\Rule;
use Illuminate\Pagination\LengthAwarePaginator;

// -- GUEST

Route::middleware('guest')->group(function () {

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
        Route::get('profile', function (RequestRequest $request) {
            $employeeId = Auth::user()->employee_id;
            $employee = Employee::findOrFail($employeeId);
            $schedule = Schedule::where('employee_id', $employee->id)->first();

            // Filter by month if provided
            $selectedMonth = $request->input('month', null);

            // Get sorting parameters
            $sortBy = $request->input('sort_by', 'week'); // Default to week
            $sortOrder = $request->input('sort_order', 'asc'); // Default to ascending

            $employee->evaluations = $employee->evaluations->sortBy(function ($evaluation) use ($sortBy, $sortOrder) {
                if ($sortBy === 'week') {
                    return intval(substr($evaluation->week, 6));
                } elseif ($sortBy === 'rating') {
                    return $evaluation->rating;
                }
            }, SORT_REGULAR, $sortOrder === 'desc');


            if ($selectedMonth) {
                $employee->evaluations = $employee->evaluations->filter(function ($evaluation) use ($selectedMonth) {
                    $yearAndWeek = explode('-W', $evaluation->week);
                    $year = $yearAndWeek[0];
                    $week = $yearAndWeek[1];
                    $date = Carbon\Carbon::now()->setISODate($year, $week);
                    return $date->format('F') === $selectedMonth;
                });
            }

            $months = $employee->evaluations->map(function ($evaluation) {
                $yearAndWeek = explode('-W', $evaluation->week);
                $year = $yearAndWeek[0];
                $week = $yearAndWeek[1];
                $date = Carbon\Carbon::now()->setISODate($year, $week);
                return $date->format('F');
            })->unique()->values();

            $employee->evaluations = $employee->evaluations()->paginate(5)
                ->appends([
                    'month' => $selectedMonth,
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder,
                ]);

            return view('profile.index', [
                'employee' => $employee,
                'schedule' => $schedule,
                'weekdays' => Shift::$weekdays,
                'months' => $months,
                'selectedMonth' => $selectedMonth,
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder
            ]);
        })->name('profile.index');

        Route::get('profile/leave-request', function () {

            $userId = Auth::user()->id;
            $leaveRequests = LeaveRequest::where('user_id', $userId)->get();
            return view('profile.leave', [
                'leaveRequests' => $leaveRequests
            ]);
        })->name('profile.leave');

        Route::post('profile/leave-request', function (LeaveRequestRequest $request) {

            LeaveRequest::create($request->validated());

            return redirect()->route('profile.leave')->with('success', 'Leave request submitted successfully.');
        })->name('profile.leave');
    });

    // For Administrator
    Route::middleware('role:Administrator')->group(function () {
        Route::get('/', function () {
            return redirect()->route('employees.index');
        });

        Route::resource('employees', EmployeeController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('shifts', ShiftController::class);
        Route::resource('schedules', ScheduleController::class);
        Route::resource('evaluations', EvaluationController::class);

        Route::get('monthly-evaluations', function (Illuminate\Http\Request $request) {
            $month = $request->input('month') ?? now()->timezone('Asia/Manila')->format('Y-m');
            $department = $request->input('department') ?? '';
            $sort = $request->input('sort') ?? 'lastname';
            $order = $request->input('order') ?? 'asc';

            $employees = Employee::when($month, function ($query, $month) {
                return $query->byMonth($month);
            })->when($department, function ($query, $department) {
                return $query->where('department_id', $department);
            })
                ->orderBy($sort, $order)->paginate(10)
                ->appends(['month' => $month, 'department' => $department, 'sort' => $sort]);

            $departments = Department::all();

            return view('evaluations.monthly', ['employees' => $employees, 'departments' => $departments]);
        })->name('evaluations.monthly.index');

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

        Route::get('administration', function () {
            return view('administration.index', ['departments' => Department::withCount('employees')->get()]);
        })->name('administration.index');

        Route::get('requests', function () {
            $leaveRequests = LeaveRequest::all();
            return view('requests.index', ['leaveRequests' => $leaveRequests]);
        })->name('requests.index');

        Route::delete('requests/{request}', function (LeaveRequest $request) {
            $request->update(['status' => 'rejected']);
            return redirect()->route('requests.index')->with('success', 'Leave request rejected successfully.');
        })->name('requests.destroy');
    });

    // For both employee and administrator
    Route::delete('logout', fn() => to_route('auth.logout'))->name('logout');

    Route::put('auth/login/{user}', function (Illuminate\Http\Request $request, User $user) {
        // Validation for name and username, password fields are nullable
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable', // New password is optional and must match confirmation
            'current_password' => 'required_with:password', // Require current password only if a new password is provided
        ]);

        // Check if the user is trying to change their password
        if ($request->filled('password')) {
            // Verify that the current password matches
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            // Hash and set the new password
            $user->password = Hash::make($validatedData['password']);
        }

        // Update name and username without requiring the current password
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
