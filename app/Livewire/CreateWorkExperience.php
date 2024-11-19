<?php

namespace App\Livewire;

use App\Models\WorkExperience;
use Livewire\Component;

class CreateWorkexperience extends Component
{
    public $workexperiences = [];
    public $appointmentStatuses = [];

    public function render()
    {
        return view('livewire.create-work-experience');
    }

    public function mount($workexperiences = [])
    {
        $this->workexperiences = $workexperiences;
        $this->appointmentStatuses = ['Regular', 'Permanent', 'Part-Time', 'Substitute', 'Contractual'];
    }

    public function addWorkExperience()
    {
        $this->workexperiences[] =  [
            'position' => '',
            'company' => '',
            'monthlysalary' => '',
            'paygrade' => '',
            'appointmentstatus' => '',
            // 'govtservice' => '',
            'start' => '',
            'end' => '',
        ];
    }


    public function removeWorkExperience($index)
    {
        unset($this->workexperiences[$index]);
        $this->workexperiences = array_values($this->workexperiences);
    }
}
