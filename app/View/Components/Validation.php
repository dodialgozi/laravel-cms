<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Validation extends Component
{
    public $selector;

    /**
     * selector => String; required; jQuery selector
     */
    public function __construct($selector)
    {
        $this->selector = $selector;
    }

    public function render()
    {
        return view('components.validation');
    }
}
