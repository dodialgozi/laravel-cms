<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormSwitch extends Component
{
    public $label;
    public $labelOn;
    public $labelOff;
    public $square;
    public $type;
    public $name;
    public $id;
    public $checked;
    public $required;
    public $title;
    public $titlePosition;
    public $horizontal;
    public $labelSize;
    public $inputSize;

    /**
     * label => String; optional
     * labelOn => String; optional
     * labelOff => String; optional
     * square => Boolean; optional
     * type => String; optional
     * name => String; optional
     * id => String; optional
     * checked => Boolean; optional
     * required => Boolean; optional
     * title => String; optional
     * titlePosition => String; optional
     * horizontal => Boolean; optional
     * labelSize => Integer; optional; (only if horizontal) 1 to 12
     * inputSize => Integer; optional; (only if horizontal) 1 to 12
     */
    public function __construct($label = null, $labelOn = 'On', $labelOff = 'Off', $square = false, $type = 'primary', $name = '', $id = null, $checked = false, $required = false, $title = null, $titlePosition = 'top', $horizontal = false, $labelSize = null)
    {
        $this->label = $label;
        $this->labelOn = $labelOn;
        $this->labelOff = $labelOff;
        $this->square = $square;
        $this->type = $type;
        $this->name = $name;
        $this->id = $id;
        $this->checked = $checked;
        $this->required = $required;
        $this->title = $title;
        $this->titlePosition = $titlePosition;
        $this->horizontal = $horizontal;
        $this->labelSize = !empty($label) ? $labelSize ?? 2 : 0;
        $this->inputSize = 12 - $this->labelSize;
    }

    public function render()
    {
        return view('components.form-switch');
    }
}
