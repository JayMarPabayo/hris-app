<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchKey = $request->input('search');
        $employees = Employee::when($searchKey, fn($query, $searchKey) => $query->search($searchKey))
            ->orderBy('lastname')
            ->paginate(15);
        return view('employee.index', ['employees' => $employees]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employee.create', [
            'departments' => Department::all(),
            'civilstatus' => Employee::$civilstatus,
            'suffixes' => Employee::$suffixes,
            'citizenships' => Employee::$citizenships,
            'bloodtypes' => Employee::$bloodtype
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request)
    {
        $employee = Employee::create($request->validated());

        // -- Add children if provided
        if ($request->has('children')) {
            foreach ($request->children as $childData) {
                $employee->children()->create($childData);
            }
        }

        // -- Add education if provided
        if ($request->has('education')) {
            foreach ($request->education as $details) {
                $employee->education()->create($details);
            }
        }

        // -- Add eligibility if provided
        if ($request->has('eligibilities')) {
            foreach ($request->eligibilities as $eligibility) {
                $employee->eligibilities()->create($eligibility);
            }
        }

        // -- Add workexperiences if provided
        if ($request->has('workexperiences')) {
            foreach ($request->workexperiences as $workexperience) {
                $employee->workexperiences()->create($workexperience);
            }
        }

        $username = strtolower(preg_replace('/\s+/', '', "$employee->firstname $employee->lastname"));
        User::create([
            'name' => "{$employee->firstname} {$employee->lastname}",
            'username' => $username,
            'role' => 'Employee',
            'password' => Hash::make('password'),
            'employee_id' => $employee->id,
            'remember_token' => Str::random(10),
        ]);

        return redirect()->route('employees.show', $employee)->with('success', 'Employee successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return view('employee.show', [
            'employee' => $employee
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        return view('employee.edit', [
            'departments' => Department::all(),
            'civilstatus' => Employee::$civilstatus,
            'suffixes' => Employee::$suffixes,
            'citizenships' => Employee::$citizenships,
            'bloodtypes' => Employee::$bloodtype,
            'employee' => $employee
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeRequest $request, Employee $employee)
    {
        $validatedData = $request->validated();
        $employee->update($validatedData);

        // -- Update children if provided
        $employee->children()->delete();
        if ($request->has('children')) {
            foreach ($request->children as $childData) {
                $employee->children()->create($childData);
            }
        }

        // -- Update education if provided
        $employee->education()->delete();
        if ($request->has('education')) {
            foreach ($request->education as $details) {
                $employee->education()->create($details);
            }
        }

        // -- Update eligibilities if provided
        $employee->eligibilities()->delete();
        if ($request->has('eligibilities')) {
            foreach ($request->eligibilities as $eligibility) {
                $employee->eligibilities()->create($eligibility);
            }
        }

        // -- Update workexperience if provided
        $employee->workexperiences()->delete();
        if ($request->has('workexperiences')) {
            foreach ($request->workexperiences as $workexperience) {
                $employee->workexperiences()->create($workexperience);
            }
        }


        return redirect()->route('employees.show', $employee)->with('success', 'Employee successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {

        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee successfully deleted.');
    }
}
