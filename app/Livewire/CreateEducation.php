<?php

namespace App\Livewire;

use Livewire\Component;

class CreateEducation extends Component
{
    public $educations = [];
    public function render()
    {
        return view('livewire.create-education');
    }

    public function mount($educations = [])
    {
        $this->educations = $educations;
    }

    public function addEducation()
    {
        $this->educations[] = [
            'level' => '',
            'school' => '',
            'degree' => '',
            'start' => '',
            'end' => '',
            'earned' => '',
            'graduated' => '',
            'accolades' => ''
        ];
    }

    public function removeEducation($index)
    {
        unset($this->educations[$index]);
        $this->educations = array_values($this->educations);
    }
}
