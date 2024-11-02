<?php

namespace App\Http\Controllers;

use App\Models\NegativeVoting;
use App\Models\SystemConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NegativeVotingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
