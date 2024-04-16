<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormTimepicker extends Component
{
    public $id;
    public $groupId;
    public $label;
    public $placeholder;
    public $function;
    public $autoInit;
    public $useSelector;
    public $noScript;
    public $horizontal;
    public $labelSize;
    public $inputSize;

    /**
     * id => String; optional
     * groupId => String; optional
     * label => String; optional
     * placeholder => String; optional
     * function => String; optional
     * autoInit => Boolean; optional
     * useSelector => Boolean; optional
     * noScript => Boolean; optional
     * horizontal => Boolean; optional
     * labelSize => Integer; optional; (only if horizontal) 1 to 12
     * inputSize => Integer; optional; (only if horizontal) 1 to 12
     */
    public function __construct($id = null, $groupId = null, $label = null, $function = null, $autoInit = true, $useSelector = false, $noScript = false, $placeholder = null, $horizontal = false, $labelSize = null)
    {
        $this->id = $id ?? (!$noScript && !$useSelector ? randomGen2(16) : null);
        $this->groupId = $groupId;
        $this->label = $label;
        $this->placeholder = $placeholder ?? (isset($label) ? strip_tags($label) : null);
        $this->function = $function ?? 'load' . strtoupper(randomGenAlpha(1)) . randomGen2(15);
        $this->autoInit = $autoInit;
        $this->useSelector = $useSelector;
        $this->noScript = $noScript;
        $this->horizontal = $horizontal;
        $this->labelSize = !empty($label) ? $labelSize ?? 2 : 0;
        $this->inputSize = 12 - $this->labelSize;
    }

    public function render()
    {
        return view('components.form-timepicker');
    }
}
