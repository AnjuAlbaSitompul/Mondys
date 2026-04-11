<?php

namespace App\View\Components\modal;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class pickModal extends Component
{
    /**
     * Create a new component instance.
     */
    public $inputId;

    public function __construct($inputId = 'scan-result')
    {
        $this->inputId = $inputId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modal.pick-modal');
    }
}
