<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormInput extends Component
{
    public $label;
    public $placeholder;
    public $prepend;
    public $prependWrap;
    public $append;
    public $appendWrap;
    public $horizontal;
    public $labelSize;
    public $inputSize;

    /**
     * label => String; optional
     * placeholder => String; optional
     * prepend => String; optional
     * prependWrap => Boolean; optional
     * append => String; optional
     * appendWrap => Boolean; optional
     * horizontal => Boolean; optional
     * labelSize => Integer; optional; (only if horizontal) 1 to 12
     * inputSize => Integer; optional; (only if horizontal) 1 to 12
     */
    public function __construct($label = null, $placeholder = null, $prepend = null, $prependWrap = true, $append = null, $appendWrap = true, $horizontal = false, $labelSize = null)
    {
        $this->label = $label;
        $this->placeholder = $placeholder ?? (isset($label) ? strip_tags($label) : null);
        $this->prepend = $prepend;
        $this->prependWrap = $prependWrap;
        $this->append = $append;
        $this->appendWrap = $appendWrap;
        $this->horizontal = $horizontal;
        $this->labelSize = !empty($label) ? $labelSize ?? 2 : 0;
        $this->inputSize = 12 - $this->labelSize;
    }

    public function render()
    {
        return view('components.form-input');
    }
}
