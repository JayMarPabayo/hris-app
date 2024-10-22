<?php

namespace App\Http\Controllers;

use App\Models\SystemConfig;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function updateMaxCredits(Request $request)
    {
        $request->validate([
            'maxCredits' => 'required|integer|min:1',
        ]);

        $config = SystemConfig::first();

        $config->maxCredits = $request->input('maxCredits');
        $config->save();

        // Redirect back with success message
        return redirect()->route('leave-request.index')->with('success', 'Max Credits updated successfully!');
    }

    public function updateMaxDays(Request $request)
    {
        $request->validate([
            'maxDays' => 'required|integer|min:1',
        ]);

        $config = SystemConfig::first();

        $config->maxDays = $request->input('maxDays');
        $config->save();

        return redirect()->route('leave-request.index')->with('success', 'Max Leave Days updated successfully!');
    }
}
