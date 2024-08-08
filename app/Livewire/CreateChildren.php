<?php

namespace App\Livewire;

use Livewire\Component;

class CreateChildren extends Component
{
    public $children = [];
    public $gender = [];


    public function render()
    {
        return view('livewire.create-children');
    }

    public function mount($children = [])
    {
        $this->gender = ['Male', 'Female'];
        $this->children = $children;
    }

    public function addChild()
    {
        $this->children[] = [
            'fullname' => '',
            'gender' => 'Male',
            'birthdate' => ''
        ];
    }

    public function removeChild($index)
    {
        unset($this->children[$index]);
        $this->children = array_values($this->children);
    }
}
