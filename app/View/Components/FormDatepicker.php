<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormDatepicker extends Component
{
    public $groupId;
    public $label;
    public $placeholder;
    public $container;
    public $format;
    public $viewMode;
    public $horizontal;
    public $labelSize;
    public $inputSize;

    /**
     * groupId => String; required
     * label => String; optional
     * placeholder => String; optional
     * container => String; optional
     * format => String; optional
     * viewMode => Integer; optional; (0 => date, 1 => month, 2 => year)
     * horizontal => Boolean; optional
     * labelSize => Integer; optional; (only if horizontal) 1 to 12
     */
    public function __construct($groupId = null, $label = null, $placeholder = null, $container = null, $format = 'yyyy-mm-dd', $viewMode = null, $horizontal = false, $labelSize = null)
    {
        $this->groupId = $groupId ?? randomGen2(16);
        $this->label = $label;
        $this->placeholder = $placeholder ?? (isset($label) ? strip_tags($label) : null);
        $this->container = $container;
        $this->format = $format;
        $this->viewMode = $viewMode;
        $this->horizontal = $horizontal;
        $this->labelSize = !empty($label) ? $labelSize ?? 2 : 0;
        $this->inputSize = 12 - $this->labelSize;
    }

    public function render()
    {
        return view('components.form-datepicker');
    }
}
