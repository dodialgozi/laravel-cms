<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputCheckbox extends Component
{
    public $id;
    public $label;
    public $type;
    public $checked;
    public $required;

    /**
     * id => String; optional
     * label => String; optional
     * type => String; optional
     * checked => Boolean; optional
     * required => Boolean; optional
     */
    public function __construct($id = null, $label = '', $type = 'primary', $checked = false, $required = false)
    {
        $this->id = $id ?? randomGen2(16);
        $this->label = $label;
        $this->type = $type;
        $this->checked = $checked;
        $this->required = $required;
    }

    public function render()
    {
        return view('components.input-checkbox');
    }
}
