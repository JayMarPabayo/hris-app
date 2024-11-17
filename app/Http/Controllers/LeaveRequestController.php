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

        return redirect()->route('administration.leave-request.index')->with('success', 'Max Credits updated successfully!');
    }
}
