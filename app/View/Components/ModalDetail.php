<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ModalDetail extends Component
{
    public $buttonClass;
    public $id;
    public $title;
    public $size;

    /**
     * buttonClass => String; required
     * id => String; optional
     * title => String; optional
     * size => String; optional
     */
    public function __construct($buttonClass, $id = 'modal-detail', $title = '', $size = 'lg')
    {
        $this->buttonClass = $buttonClass;
        $this->id = $id;
        $this->title = $title;
        $this->size = $size;
    }

    public function render()
    {
        return view('components.modal-detail');
    }
}
