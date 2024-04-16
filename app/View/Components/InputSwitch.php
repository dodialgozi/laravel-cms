<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputSwitch extends Component
{
    public $id;
    public $labelOn;
    public $labelOff;
    public $type;
    public $square;
    public $checked;
    public $title;
    public $titlePosition;

    /**
     * id => String; optional
     * labelOn => String; optional
     * labelOff => String; optional
     * type => String; optional
     * square => Boolean; optional
     * checked => Boolean; optional
     * title => String; optional
     * titlePosition => String; optional
     */
    public function __construct($id = null, $labelOn = 'On', $labelOff = 'Off', $type = 'primary', $square = false, $checked = false, $title = null, $titlePosition = 'top')
    {
        $this->id = $id ?? randomGen2(16);
        $this->labelOn = $labelOn;
        $this->labelOff = $labelOff;
        $this->type = $type;
        $this->square = $square;
        $this->checked = $checked;
        $this->title = $title;
        $this->titlePosition = $titlePosition;
    }

    public function render()
    {
        return view('components.input-switch');
    }
}
