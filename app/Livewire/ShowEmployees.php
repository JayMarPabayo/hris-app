<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\Employee;
use Livewire\Component;

class ShowEmployees extends Component
{
    public $employees = [];
    public $departments = [];
    public $designations = [];
    public $department = '';
    public $designation = '';

    public $withDesignation = false;

    public function render()
    {
        return view('livewire.show-employees');
    }

    public function mount($withDesignation = false)
    {
        $this->withDesignation = $withDesignation;
        $this->departments = Department::all();
        $this->designations = [];
    }

    public function updatedDepartment()
    {

        $this->designations = [];

        if ($this->department) {
            $this->designations =  Employee::byDepartment($this->department)->select('designation')->distinct()->pluck('designation')->toArray();
        }
    }

    public function getEmployeesByDepartment()
    {
        $query = Employee::query();

        if ($this->department) {
            $query->where('department_id', $this->department);

            if ($this->designation) {
                $query->where('designation', $this->designation);
            }
        } else {
            $this->employees = [];
            return;
        }

        $this->employees = $query->get();
    }
}
