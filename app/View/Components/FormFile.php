<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormFile extends Component
{
    public $label;
    public $placeholder;
    public $value;
    public $prepend;
    public $append;
    public $image;
    public $thumbnail;
    public $download;
    public $horizontal;
    public $labelSize;
    public $inputSize;

    /**
     * label => String; optional
     * placeholder => String; optional
     * value => String; optional
     * prepend => String; optional
     * append => String; optional
     * image => Boolean; optional
     * thumbnail => Integer; optional
     * download => Boolean; optional
     * horizontal => Boolean; optional
     * labelSize => Integer; optional; (only if horizontal) 1 to 12
     * inputSize => Integer; optional; (only if horizontal) 1 to 12
     */
    public function __construct($label = null, $placeholder = null, $value = null, $prepend = null, $append = null, $image = false, $thumbnail = 150, $download = true, $horizontal = false, $labelSize = null)
    {
        $this->label = $label;
        $this->placeholder = $placeholder ?? (isset($label) ? strip_tags($label) : null);
        $this->value = $value;
        $this->prepend = $prepend;
        $this->append = $append;
        $this->image = $image;
        $this->thumbnail = $thumbnail;
        $this->download = $download;
        $this->horizontal = $horizontal;
        $this->labelSize = !empty($label) ? $labelSize ?? 2 : 0;
        $this->inputSize = 12 - $this->labelSize;
    }

    public function render()
    {
        return view('components.form-file');
    }
}
