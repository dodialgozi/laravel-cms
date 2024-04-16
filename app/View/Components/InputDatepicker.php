<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputDatepicker extends Component
{
    public $container;
    public $format;
    public $viewMode;

    /**
     * container => String; optional
     * format => String; optional
     * viewMode => Integer; optional; (0 => date, 1 => month, 2 => year)
     */
    public function __construct($container = null, $format = 'yyyy-mm-dd', $viewMode = null)
    {
        $this->container = $container;
        $this->format = $format;
        $this->viewMode = $viewMode;
    }

    public function render()
    {
        return view('components.input-datepicker');
    }
}
