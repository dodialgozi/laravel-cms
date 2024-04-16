<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputTextarea extends Component
{
    public $value;

    /**
     * value => String; optional
     */
    public function __construct($value = '')
    {
        $this->value = $value;
    }

    public function render()
    {
        return view('components.input-textarea');
    }
}
