<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use App\Models\CustomTime;
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
        $selectedDay = $request->input('day');

        $schedules = Schedule::with(['employee', 'shift'])
            ->when($selectedDay, function ($query, $selectedDay) {
                return $query->whereHas('shift', function ($shiftQuery) use ($selectedDay) {
                    $shiftQuery->whereJsonContains('weekdays', $selectedDay);
                });
            })
            ->get()
            ->groupBy('employee.designation');

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

        if (!$request->has('dayoffs') || empty($request->dayoffs)) {
            $validatedData['dayoffs'] = [];
        }

        $shiftChanged = $request->shift_id != $schedule->shift_id;

        if ($shiftChanged) {
            CustomTime::where('schedule_id', $schedule->id)->delete();
        }

        // Update the schedule with the validated data
        $schedule->update($validatedData);

        // Only process custom times if the shift did not change
        if (!$shiftChanged) {
            $days = $request->input('day', []);
            $startTimes = $request->input('start_time', []);
            $endTimes = $request->input('end_time', []);

            foreach ($days as $index => $day) {
                if (!empty($startTimes[$index]) && !empty($endTimes[$index])) {
                    CustomTime::updateOrCreate(
                        [
                            'day' => $day,
                            'schedule_id' => $schedule->id,
                        ],
                        [
                            'start_time' => $startTimes[$index],
                            'end_time' => $endTimes[$index],
                        ]
                    );
                }
            }
        }

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
