<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DoctorApproval extends Component
{
    /**
     * Create a new component instance.
     */
    public $doctorName;
    public function __construct($doctorName)
    {
        $this->doctorName = $doctorName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.doctor-approval');
    }
}
