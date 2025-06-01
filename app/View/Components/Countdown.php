<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Countdown extends Component
{
    public $date;
    public $title;
    /**
     * Create a new component instance.
     */
    public function __construct($date = null, $title = '')
    {
        $this->date=$date;
        $this->title = $title;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.countdown');
    }
}
