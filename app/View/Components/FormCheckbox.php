<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormCheckbox extends Component
{
    public $label;
    public $name;
    public $id;
    public $options;
    public $value;
    public $strictValue;
    public $required;
    public $listing;
    public $horizontal;
    public $labelSize;
    public $inputSize;

    /**
     * label => String; optional
     * name => String; optional
     * id => String; optional
     * options => Array; optional
     * value => String; optional
     * strictValue => Boolean; optional
     * required => Boolean; optional
     * listing => Boolean; optional
     * horizontal => Boolean; optional
     * labelSize => Integer; optional; (only if horizontal) 1 to 12
     * inputSize => Integer; optional; (only if horizontal) 1 to 12
     */
    public function __construct($label = null, $name = '', $id = null, $options = [], $value = null, $strictValue = false, $required = false, $listing = false, $horizontal = false, $labelSize = null)
    {
        $this->label = $label;
        $this->name = $name;
        $this->id = $id;
        $this->options = $options;
        $this->value = $value;
        $this->strictValue = $strictValue;
        $this->required = $required;
        $this->listing = $listing;
        $this->horizontal = $horizontal;
        $this->labelSize = !empty($label) ? $labelSize ?? 2 : 0;
        $this->inputSize = 12 - $this->labelSize;
    }

    public function render()
    {
        return view('components.form-checkbox');
    }
}
