<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use App\Models\CustomTime;
use App\Models\Employee;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\SwapRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;


class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $selectedDay = $request->input('day');
        $week = $request->input('week') ?? date('Y-\WW');

        $weeksWithSchedules = Schedule::distinct()
            ->pluck('week')
            ->toArray();

        $maxIterations = 52; // Limit to one year of weeks
        $iterations = 0;

        do {
            $schedules = Schedule::where('week', $week)
                ->with(['employee', 'shift'])
                ->when($selectedDay, function ($query, $selectedDay) {
                    return $query->whereHas('shift', function ($shiftQuery) use ($selectedDay) {
                        $shiftQuery->whereJsonContains('weekdays', $selectedDay);
                    })
                        ->whereJsonDoesntContain('dayoffs', $selectedDay);
                })
                ->get()
                ->groupBy('employee.designation');

            if ($schedules->isEmpty()) {
                $week = date('Y-\WW', strtotime('+1 week', strtotime($week)));
                $iterations++;

                if ($iterations > $maxIterations) {
                    $week = date('Y-\WW');
                    $schedules = collect();
                    break;
                }
            } else {
                break;
            }
        } while (true);

        $selectedWeekEndDate = Carbon::parse($week . '-7');
        $isPastWeek = $selectedWeekEndDate->isPast();

        return view('schedules.index', [
            'schedules' => $schedules,
            'shifts' => Shift::all(),
            'weekdays' => Shift::$weekdays,
            'weeksWithSchedules' => $weeksWithSchedules,
            'selectedDay' => $selectedDay,
            'week' => $week,
            'hasAnyPendingRequest' => SwapRequest::hasAnyPendingRequest(),
            'isPastWeek' => $isPastWeek,
        ]);
    }


    public function create(Request $request)
    {

        $searchKey = $request->input('search');
        $week = $request->input('week');

        $employees = Employee::when($searchKey, fn($query, $searchKey) => $query->search($searchKey))
            ->whereDoesntHave('schedules', function ($query) use ($week) {
                if ($week) {
                    $query->where('week', $week);
                }
            })
            ->orderBy('lastname')
            ->paginate(10);

        return view('schedules.create', [
            'employees' => $employees,
            'shifts' => Shift::all(),
            'week' => $week,
        ]);
    }

    public function store(ScheduleRequest $request)
    {
        $data = $request->validated();

        Schedule::create($data);

        return redirect()->route('schedules.create', ['week' => $data['week']])->with('success', 'Schedule added.');
    }
    public function update(Request $request, Schedule $schedule)
    {
        $validatedData = $request->validate([
            'dayoffs' => 'nullable|array',

        ]);
        $week = $request->input('week') ?? date('Y-\WW');

        if (!$request->has('dayoffs') || empty($request->dayoffs)) {
            $validatedData['dayoffs'] = [];
        }

        $schedule->update($validatedData);

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

        return redirect()->route('schedules.index', ['week' => $week])->with('success', 'Schedule successfully updated.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Schedule successfully deleted.');
    }
}
