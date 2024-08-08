<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftRequest;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('shifts.index', [
            'shifts' => Shift::all(),
            'weekdays' => Shift::$weekdays
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShiftRequest $request)
    {
        $data = $request->validated();
        Shift::create($data);

        return redirect()->route('shifts.index')->with('success', 'Shift successfully added.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShiftRequest $request, Shift $shift)
    {
        $validatedData = $request->validated();
        $shift->update($validatedData);


        return redirect()->route('shifts.index')->with('success', 'Shift successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shift $shift)
    {
        $shift->delete();
        return redirect()->route('shifts.index')->with('success', 'Shift successfully deleted.');
    }
}
