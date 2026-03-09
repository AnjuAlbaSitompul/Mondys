<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class inputBtn extends Component
{


    public function __construct(
        public $id = null,
        public $label = null,
        public $type = 'text',
        public $placeholder = '',
        public $btnTxt = '',
        public $invalid = '',
        public $disabled = false
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.input-btn');
    }
}
