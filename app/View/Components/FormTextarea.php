<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormTextarea extends Component
{
    public $label;
    public $placeholder;
    public $value;
    public $horizontal;
    public $labelSize;
    public $inputSize;

    /**
     * label => String; optional
     * placeholder => String; optional
     * value => String; optional
     * horizontal => Boolean; optional
     * labelSize => Integer; optional; (only if horizontal) 1 to 12
     * inputSize => Integer; optional; (only if horizontal) 1 to 12
     */
    public function __construct($label = null, $placeholder = null, $value = '', $horizontal = false, $labelSize = null)
    {
        $this->label = $label;
        $this->placeholder = $placeholder ?? (isset($label) ? strip_tags($label) : null);
        $this->value = $value;
        $this->horizontal = $horizontal;
        $this->labelSize = !empty($label) ? $labelSize ?? 2 : 0;
        $this->inputSize = 12 - $this->labelSize;
    }

    public function render()
    {
        return view('components.form-textarea');
    }
}
