<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormDaterangepicker extends Component
{
    public $label;
    public $id;
    public $format;
    public $placeholderStart;
    public $placeholderEnd;
    public $nameStart;
    public $nameEnd;
    public $valueStart;
    public $valueEnd;
    public $horizontal;
    public $labelSize;
    public $inputSize;

    /**
     * label => String; optional
     * id => String; optional
     * format => String; optional
     * placeholderStart => String; optional
     * placeholderEnd => String; optional
     * nameStart => String; optional
     * nameEnd => String; optional
     * valueStart => String; optional
     * valueEnd => String; optional
     * horizontal => Boolean; optional
     * labelSize => Integer; optional; (only if horizontal) 1 to 12
     * inputSize => Integer; optional; (only if horizontal) 1 to 12
     */
    public function __construct($label = null, $id = null, $format = 'yyyy-mm-dd', $placeholderStart = '', $placeholderEnd = '', $nameStart = '', $nameEnd = '', $valueStart = '', $valueEnd = '', $horizontal = false, $labelSize = null)
    {
        $this->label = $label;
        $this->id = $id;
        $this->format = $format;
        $this->placeholderStart = $placeholderStart;
        $this->placeholderEnd = $placeholderEnd;
        $this->nameStart = $nameStart;
        $this->nameEnd = $nameEnd;
        $this->valueStart = $valueStart;
        $this->valueEnd = $valueEnd;
        $this->horizontal = $horizontal;
        $this->labelSize = !empty($label) ? $labelSize ?? 2 : 0;
        $this->inputSize = 12 - $this->labelSize;
    }

    public function render()
    {
        return view('components.form-daterangepicker');
    }
}
