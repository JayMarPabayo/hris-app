<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('administration.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartmentRequest $request)
    {
        Department::create($request->validated());

        return redirect()->route('administration.index')->with('success', 'Department successfully added.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentRequest $request, Department $department)
    {
        $validatedData = $request->validated();
        $department->update($validatedData);


        return redirect()->route('administration.index')->with('success', 'Department successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('administration.index')->with('success', 'Department successfully deleted.');
    }
}
