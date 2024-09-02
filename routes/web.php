<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Shift;
use App\Models\Schedule;
use App\Models\User;

use Illuminate\Validation\Rule;

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
            return redirect()->intended('/');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    })->name('auth.login');
});

// -- USERS
Route::middleware('auth')->group(function () {

    Route::delete('logout', fn() => to_route('auth.logout'))->name('logout');

    Route::put('auth/login/{user}', function (Illuminate\Http\Request $request, User $user) {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'password' => 'required|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ]
        ]);
        $user->update($validatedData);
        return redirect()->back()->with('success', 'Account Updated.');
    })->name('auth.update');

    Route::delete('auth/logout', function () {
        Auth::logout();
        Request::session()->invalidate();
        Request::session()->regenerateToken();
        return redirect('/');
    })->name('auth.logout');

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
});
