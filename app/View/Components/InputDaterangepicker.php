<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputDaterangepicker extends Component
{
    public $id;
    public $format;
    public $placeholderStart;
    public $placeholderEnd;
    public $nameStart;
    public $nameEnd;
    public $valueStart;
    public $valueEnd;

    /**
     * id => String; optional
     * format => String; optional
     * placeholderStart => String; optional
     * placeholderEnd => String; optional
     * nameStart => String; optional
     * nameEnd => String; optional
     * valueStart => String; optional
     * valueEnd => String; optional
     */
    public function __construct($id = null, $format = 'yyyy-mm-dd', $placeholderStart = '', $placeholderEnd = '', $nameStart = '', $nameEnd = '', $valueStart = '', $valueEnd = '')
    {
        $this->id = $id ?? randomGen2(16);
        $this->format = $format;
        $this->placeholderStart = $placeholderStart;
        $this->placeholderEnd = $placeholderEnd;
        $this->nameStart = $nameStart;
        $this->nameEnd = $nameEnd;
        $this->valueStart = $valueStart;
        $this->valueEnd = $valueEnd;
    }

    public function render()
    {
        return view('components.input-daterangepicker');
    }
}
