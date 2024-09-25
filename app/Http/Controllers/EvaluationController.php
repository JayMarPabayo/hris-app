<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EvaluationRequest;
use App\Models\Employee;
use App\Models\Evaluation;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchKey = $request->input('search');
        $sortOrder = $request->input('sort', 'desc');

        $currentWeek = date('W');

        $formattedWeek = sprintf("%d-W%02d", date('Y'), $currentWeek);

        $week = $request->input('week');

        if ($week) {
            $formattedWeek = $week;
        }


        $evaluations = Evaluation::when($searchKey, function ($query, $searchKey) {
            return $query->search($searchKey);
        })
            ->where('week', $formattedWeek)
            ->orderBy('rating', $sortOrder)
            ->paginate(10);

        return view('evaluations.index', [
            'evaluations' => $evaluations,
            'currentWeek' => $formattedWeek
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $searchKey = $request->input('search');

        $currentWeek = date('W');

        $formattedWeek = sprintf("%d-W%02d", date('Y'), $currentWeek);

        $week = $request->input('week') ?? $formattedWeek;

        $employees = Employee::when($searchKey, fn($query, $searchKey) => $query->search($searchKey))
            ->whereDoesntHave('evaluations', function ($query) use ($week) {
                $query->where('week', $week);
            })
            ->orderBy('lastname')
            ->paginate(10);

        return view('evaluations.create', [
            'employees' => $employees,
            'week' => $week
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluationRequest $request)
    {
        $data = $request->validated();
        Evaluation::create($data);

        return redirect()->route('evaluations.create')->with('success', 'Evaluation added.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluationRequest $request, Evaluation $evaluation)
    {
        $validatedData = $request->validated();
        $evaluation->update($validatedData);

        return redirect()->route('evaluations.index')->with('success', 'Evaluation successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();
        return redirect()->route('evaluations.index')->with('success', 'Evaluation successfully removed.');
    }
}
