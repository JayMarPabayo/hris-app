<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RatingRemarks extends Component
{
    public $rating;
    public $remarks;
    /**
     * Create a new component instance.
     *
     * @param int $rating
     */
    public function __construct($rating)
    {
        $this->rating = $rating;
        $this->remarks = $this->getRemarks();
    }


    private function getRemarks()
    {
        switch ($this->rating) {
            case 1:
                return 'Bad';
            case 2:
                return 'Not Good';
            case 3:
                return 'Good';
            case 4:
                return 'Very Good';
            case 5:
                return 'Excellent';
            default:
                return 'Invalid rating';
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.rating-remarks');
    }
}
