<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Evaluation;
use App\Models\SystemConfig;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $currentMonth = $request->input('month') ?? date('Y-m');

        $employeesByDepartment = Employee::query()->with('department')
            ->get()
            ->groupBy('department.name')
            ->map(function ($employees) use ($currentMonth) {
                return $employees->sortByDesc(function ($employee) use ($currentMonth) {
                    $evaluations = Evaluation::where('coworker_id', $employee->id)
                        ->whereMonth('created_at', date('m', strtotime($currentMonth)))
                        ->whereYear('created_at', date('Y', strtotime($currentMonth)));

                    $avgRating = $evaluations->avg('rating');
                    $evaluationCount = $evaluations->distinct('employee_id')->count('employee_id');

                    $employee->avg_rating = $avgRating;
                    $employee->evaluation_count = $evaluationCount;

                    return $avgRating;
                });
            });

        return view('evaluation.index', [
            'employeesByDepartment' => $employeesByDepartment,
            'currentMonth' => $currentMonth,
        ]);
    }


    public function updateEvaluation(Request $request)
    {
        $request->validate([
            'evaluation' => 'required|boolean',
        ]);

        $config = SystemConfig::first();

        $config->evaluation = $request->input('evaluation');
        $config->save();

        $message = $config->evaluation ? 'Monthly Evaluation is now open!' : 'Monthly Evaluation is now closed.';

        return redirect()->route(route: 'evaluations.index')->with('success',  $message);
    }
}
