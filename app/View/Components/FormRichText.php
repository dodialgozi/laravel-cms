<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormRichText extends Component
{
    public $label;
    public $placeholder;
    public $horizontal;
    public $labelSize;
    public $inputSize;
    public $name;
    public $id;

    /**
     * name => String; optional
     * label => String; optional
     * placeholder => String; optional
     * value => String; optional
     * horizontal => Boolean; optional
     * labelSize => Integer; optional; (only if horizontal) 1 to 12
     * inputSize => Integer; optional; (only if horizontal) 1 to 12
     */
    public function __construct($name = null, $id = null, $label = null, $placeholder = null, $horizontal = false, $labelSize = null)
    {
        $this->label = $label;
        $this->placeholder = $placeholder ?? (isset($label) ? strip_tags($label) : null);
        $this->horizontal = $horizontal;
        $this->labelSize = !empty($label) ? $labelSize ?? 2 : 0;
        $this->inputSize = 12 - $this->labelSize;
        $this->name = $name;
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-rich-text');
    }
}
