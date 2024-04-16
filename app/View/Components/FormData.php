<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormData extends Component
{
    public $label;
    public $value;
    public $prepend;
    public $prependWrap;
    public $append;
    public $appendWrap;
    public $clean;
    public $horizontal;
    public $labelSize;
    public $inputSize;

    /**
     * label => String; optional
     * value => String; optional
     * prepend => String; optional
     * prependWrap => Boolean; optional
     * append => String; optional
     * appendWrap => Boolean; optional
     * clean => Boolean; optional
     * horizontal => Boolean; optional
     * labelSize => Integer; optional; (only if horizontal) 1 to 12
     * inputSize => Integer; optional; (only if horizontal) 1 to 12
     */
    public function __construct($label = null, $value = '', $prepend = null, $prependWrap = true, $append = null, $appendWrap = true, $clean = false, $horizontal = false, $labelSize = null)
    {
        $this->label = $label;
        $this->value = $value;
        $this->prepend = $prepend;
        $this->prependWrap = $prependWrap;
        $this->append = $append;
        $this->appendWrap = $appendWrap;
        $this->clean = $clean;
        $this->horizontal = $horizontal;
        $this->labelSize = !empty($label) ? $labelSize ?? 2 : 0;
        $this->inputSize = 12 - $this->labelSize;
    }

    public function render()
    {
        return view('components.form-data');
    }
}
