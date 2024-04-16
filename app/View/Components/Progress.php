<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Progress extends Component
{
    public $type;
    public $value;

    public function __construct($type = null, $value)
    {
        $this->type = $type ?? 'primary';
        $this->value = $value;
    }

    public function render()
    {
        return view('components.progress');
    }
}
