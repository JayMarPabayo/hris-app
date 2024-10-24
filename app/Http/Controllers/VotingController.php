<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SystemConfig;
use Illuminate\Http\Request;
use App\Models\Voting;
use Illuminate\Support\Facades\Auth;

class VotingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $userVoted = Voting::where('month', date('Y-m'))
            ->where('user_id', Auth::user()->id)
            ->first();

        if ($userVoted) {
            abort(403, 'You have already voted for this month.');
        }

        $searchKey = $request->input('search');

        $currentMonth = date('Y-m');

        $month = $request->input('month');

        if ($month) {
            $currentMonth = $month;
        }

        $employees = Employee::when($searchKey, fn($query, $searchKey) => $query->search($searchKey))
            ->byMonth($currentMonth)
            ->orderBy('total_votes', 'desc')
            ->paginate(15);

        return view('eom-results.index', [
            'employees' => $employees,
            'currentMonth' => $currentMonth
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $userVoted = Voting::where('month', date('Y-m'))
            ->where('user_id', Auth::user()->id)
            ->first();

        $systemConfig = SystemConfig::first();
        $isVotingOpen = $systemConfig ? $systemConfig->isVotingOpen() : false;

        if ($userVoted) {
            abort(403, 'You have already voted for this month.');
        }

        if (!$isVotingOpen) {
            abort(403, 'Voting is still not open.');
        }

        $searchKey = $request->input('search');

        $loggedInEmployeeId = Auth::user()->employee_id;

        $employees = Employee::when($searchKey, fn($query, $searchKey) => $query->search($searchKey))
            ->where('id', '!=', $loggedInEmployeeId)
            ->orderBy('lastname')
            ->paginate(15);

        return view('eom-results.create', ['employees' => $employees]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|date_format:Y-m',
            'remarks' => 'nullable|string',
        ]);

        $validatedData['user_id'] = Auth::user()->id;

        $existingVote = Voting::where('month', $validatedData['month'])
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

        Voting::create($validatedData);

        return redirect()->route('profile.index')->with('success', 'Your vote has been submitted!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateEOMVoting(Request $request)
    {
        $request->validate([
            'eomVoting' => 'required|boolean',
        ]);

        $config = SystemConfig::first();

        $config->eomVoting = $request->input('eomVoting');
        $config->save();

        $message = $config->eomVoting ? 'EOM Voting is now open!' : 'EOM Voting is now closed.';

        return redirect()->route('employee-of-the-month.index')->with('success',  $message);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Request $request) {}
}
