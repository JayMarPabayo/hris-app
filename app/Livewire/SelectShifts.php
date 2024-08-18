<?php

namespace App\Livewire;

use App\Models\Shift;
use Livewire\Component;

use function Laravel\Prompts\select;

class SelectShifts extends Component
{

    public $selectedShift = 0;
    public $shift = null;
    public $shifts = [];


    public function render()
    {
        return view('livewire.select-shifts');
    }

    public function mount($shiftId = null)
    {
        $this->shifts = Shift::all();
        if ($shiftId) {
            $this->selectedShift = $shiftId;
        }
    }

    public function updatedSelectedShift()
    {

        $this->shift = [];

        if ($this->selectedShift) {
            $this->shift = Shift::findOrFail($this->selectedShift);
        }
    }
}
