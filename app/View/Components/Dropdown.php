<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Dropdown extends Component
{
    public $id;

    /**
     * id => String; optional
     */
    public function __construct($id = null)
    {
        $this->id = $id ?? randomGen2(12);
    }

    public function render()
    {
        return view('components.dropdown');
    }
}
