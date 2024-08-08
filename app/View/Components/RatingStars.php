<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RatingStars extends Component
{
    public $rating;

    /**
     * Create a new component instance.
     *
     * @param float $rating
     * @return void
     */

    public function __construct($rating)
    {
        $this->rating = $rating;
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.rating-stars');
    }
}
