<?php

namespace App\Livewire;

use App\Models\Employee;
use Livewire\Component;

class ShowEmployee extends Component
{
    public $id = null;
    public $employee = null;

    public function render()
    {
        return view('livewire.show-employee');
    }

    public function searchEmployee()
    {

        if ($this->id) {
            $this->employee = Employee::find($this->id);
        } else {
            $this->employee = null;
        }
    }
}
