<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EmployeesList extends Component
{
    public $employees;
    public $mode;
    public function __construct($employees, $mode)
    {
        $this->employees = $employees;
        $this->mode = $mode;
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.employees-list');
    }
}
