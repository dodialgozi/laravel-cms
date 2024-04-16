<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputData extends Component
{
    public $value;
    public $prepend;
    public $prependWrap;
    public $append;
    public $appendWrap;

    /**
     * value => String; optional
     * prepend => String; optional
     * prependWrap => Boolean; optional
     * append => String; optional
     * appendWrap => Boolean; optional
     */
    public function __construct($value = '', $prepend = null, $prependWrap = true, $append = null, $appendWrap = true)
    {
        $this->value = $value;
        $this->prepend = $prepend;
        $this->prependWrap = $prependWrap;
        $this->append = $append;
        $this->appendWrap = $appendWrap;
    }

    public function render()
    {
        return view('components.input-data');
    }
}
