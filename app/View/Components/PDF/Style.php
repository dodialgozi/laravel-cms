<?php

namespace App\View\Components\Pdf;

use Illuminate\View\Component;

class Style extends Component
{
    /**
     */
    public function __construct()
    {
    }

    public function render()
    {
        return view('components.pdf.style');
    }
}
