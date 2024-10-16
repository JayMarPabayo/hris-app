<?php

namespace App\Livewire;

use App\Models\Employee;
use Livewire\Component;

class ShowEmployee extends Component
{
    public $search = '';
    public $employee = null;

    public function render()
    {
        return view('livewire.show-employee');
    }

    public function searchEmployee()
    {
        if ($this->search) {
            if (is_numeric($this->search)) {
                $this->employee = Employee::where('id', $this->search)->first();
            } else {
                $this->employee = Employee::when($this->search, fn($query, $searchKey) => $query->search($searchKey))
                    ->first();
            }
        } else {
            $this->employee = null;
        }
    }
}
