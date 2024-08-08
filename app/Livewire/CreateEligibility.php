<?php

namespace App\Livewire;

use Livewire\Component;

class CreateEligibility extends Component
{
    public $eligibilities = [];
    public function render()
    {
        return view('livewire.create-eligibility');
    }

    public function mount($eligibilities = [])
    {
        $this->eligibilities = $eligibilities;
    }

    public function addEligibility()
    {
        $this->eligibilities[] = [
            'examination' => '',
            'rating' => '',
            'examdate' => '',
            'address' => '',
            'license' => '',
            'validity' => ''
        ];
    }


    public function removeEligibility($index)
    {
        unset($this->eligibilities[$index]);
        $this->eligibilities = array_values($this->eligibilities);
    }
}
