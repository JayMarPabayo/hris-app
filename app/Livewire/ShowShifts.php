<?php

namespace App\Livewire;

use App\Models\Schedule;
use Livewire\Component;
use App\Models\Shift;


class ShowShifts extends Component
{

    public $selectedShift = '';
    public $schedules = [];
    public $shifts;
    public $sort = 'asc';
    public $week = '';

    public function render()
    {
        return view('livewire.show-shifts');
    }

    public function mount()
    {
        $this->shifts = Shift::all();

        $this->selectedShift = '0';

        $this->week = '2024-W52';

        $this->getEmployeesByShift();
    }

    public function getEmployeesByShift()
    {
        $this->schedules = [];

        $query = Schedule::query();

        if ($this->selectedShift && $this->selectedShift !== 0) {
            $query->where('shift_id', $this->selectedShift);
        } else if ($this->selectedShift == '') {
            $this->schedules = [];
            return;
        }

        $this->schedules = $query->where('week', $this->week)->join('employees', 'schedules.employee_id', '=', 'employees.id')
            ->orderBy('employees.lastname', $this->sort)->get();
    }

    public function setSort()
    {
        if ($this->sort == 'asc') {
            $this->sort = 'desc';
        } else {
            $this->sort = 'asc';
        }

        $this->getEmployeesByShift();
    }

    public function getShift()
    {
        if ($this->selectedShift) {
            return Shift::findOrFail($this->selectedShift);
        } else {
            return 'all-shifts';
        }
    }
}
