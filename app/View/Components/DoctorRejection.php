<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DoctorRejection extends Component
{
    /**
     * Create a new component instance.
     */
    public $doctorName;
    public $rejectionReason;
    public function __construct($doctorName,$rejectionReason)
    {
        $this->doctorName = $doctorName;
        $this->rejectionReason = $rejectionReason;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.doctor-rejection');
    }
}
