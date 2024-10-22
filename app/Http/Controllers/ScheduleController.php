<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use App\Models\Employee;
use App\Models\Schedule;
use App\Models\Shift;
use Illuminate\Http\Request;


class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchKey = $request->input('search');
        $shiftId = $request->input('shift');
        $selectedDay = $request->input('day');

        $schedules = Schedule::when($shiftId, function ($query, $shiftId) {
            return $query->where('shift_id', '=', $shiftId);
        })
            ->when($searchKey, function ($query, $searchKey) {
                return $query->search($searchKey);
            })
            ->when($selectedDay, function ($query, $selectedDay) {
                if ($selectedDay) {
                    return $query->whereHas('shift', function ($shiftQuery) use ($selectedDay) {
                        $shiftQuery->whereJsonContains('weekdays', $selectedDay);
                    });
                }
            })
            ->join('employees', 'schedules.employee_id', '=', 'employees.id')
            ->orderBy('employees.lastname', 'asc')
            ->paginate(10);

        return view('schedules.index', [
            'schedules' => $schedules,
            'shifts' => Shift::all(),
            'weekdays' => Shift::$weekdays,
            'selectedDay' => $selectedDay
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $searchKey = $request->input('search');
        $employees = Employee::when($searchKey, fn($query, $searchKey) => $query->search($searchKey))
            ->whereDoesntHave('schedules')
            ->orderBy('lastname')
            ->paginate(10);

        return view('schedules.create', [
            'employees' => $employees,
            'shifts' => Shift::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScheduleRequest $request)
    {
        $data = $request->validated();
        Schedule::create($data);

        return redirect()->route('schedules.create')->with('success', 'Schedule added.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScheduleRequest $request, Schedule $schedule)
    {

        $validatedData = $request->validated();
        $schedule->update($validatedData);

        return redirect()->route('schedules.index')->with('success', 'Schedule successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Schedule successfully deleted.');
    }
}
