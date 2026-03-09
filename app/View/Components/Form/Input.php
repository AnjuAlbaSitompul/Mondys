<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public $label;
    public $name;
    public $placeholder;
    public $type;
    public $value;
    public $id;
    public $valid;
    public function __construct($label, $name, $type = 'text', $value = null, $placeholder = null, $id = "", $valid)
    {
        $this->label = $label;
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->id = $id;
        $this->valid = $valid;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.input');
    }
}
