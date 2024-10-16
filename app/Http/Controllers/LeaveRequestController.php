<?php

namespace App\Http\Controllers;

use App\Models\SystemConfig;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function updateMaxCredits(Request $request)
    {
        // Validate the request
        $request->validate([
            'maxCredits' => 'required|integer|min:1',
        ]);

        // Get the first config record
        $config = SystemConfig::first();

        // Update maxCredits field
        $config->maxCredits = $request->input('maxCredits');
        $config->save();

        // Redirect back with success message
        return redirect()->route('leave-request.index')->with('success', 'Max Credits updated successfully!');
    }

    public function updateMaxDays(Request $request)
    {
        // Validate the request
        $request->validate([
            'maxDays' => 'required|integer|min:1',
        ]);

        // Get the first config record
        $config = SystemConfig::first();

        // Update maxDays field
        $config->maxDays = $request->input('maxDays');
        $config->save();

        // Redirect back with success message
        return redirect()->route('leave-request.index')->with('success', 'Max Leave Days updated successfully!');
    }
}
