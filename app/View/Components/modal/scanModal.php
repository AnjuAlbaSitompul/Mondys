<?php

namespace App\View\Components\modal;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class scanModal extends Component
{
    /**
     * Create a new component instance.
     */
    public $inputId;

    public function __construct($inputId = 'scan-result')
    {
        $this->inputId = $inputId;
    }

    public function render()
    {
        return view('components.modal.scan-modal');
    }
}
