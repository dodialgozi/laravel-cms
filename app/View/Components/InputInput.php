<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputInput extends Component
{
    public $prepend;
    public $prependWrap;
    public $append;
    public $appendWrap;

    /**
     * prepend => String; optional
     * prependWrap => Boolean; optional
     * append => String; optional
     * appendWrap => Boolean; optional
     */
    public function __construct($prepend = null, $prependWrap = true, $append = null, $appendWrap = true)
    {
        $this->prepend = $prepend;
        $this->prependWrap = $prependWrap;
        $this->append = $append;
        $this->appendWrap = $appendWrap;
    }

    public function render()
    {
        return view('components.input-input');
    }
}
