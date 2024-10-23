<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voting;

class VotingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchKey = $request->input('search');
        $sortOrder = $request->input('sort', 'desc');

        $currentMonth = date('M');

        $month = $request->input('month');

        if ($month) {
            $currentMonth = $month;
        }


        $votes = Voting::when($searchKey, function ($query, $searchKey) {
            return $query->search($searchKey);
        })
            ->where('month', $currentMonth)
            ->orderBy('rating', $sortOrder)
            ->paginate(10);

        return view('eom-results.index', [
            'votes' => $votes,
            'currentMonth' => $currentMonth
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request) {}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request) {}

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Request $request) {}
}
