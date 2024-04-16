<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;

class Modal extends Component
{
    public $id;
    public $size;
    public $title;
    public $isContent;
    public $form;
    public $header;
    public $footer;
    public $withoutHeader;
    public $withoutFooter;

    /**
     * id => String; optional
     * size => String; optional
     * title => String; optional
     * isContent => Boolean; optional
     * form => Array; insert tag form with attributes in array; optional
     * header => Slot; optional
     * footer => Slot; optional
     * withoutHeader => Boolean; optional
     * withoutFooter => Boolean; optional
     */
    public function __construct($id = null, $size = 'md', $title = '', $isContent = false, $form = null, $header = null, $footer = null, $withoutHeader = false, $withoutFooter = false)
    {
        $this->id = $id;
        $this->size = $size;
        $this->title = $title;
        $this->isContent = $isContent;
        $this->form = isset($form) && is_array($form) ? new ComponentAttributeBag($form) : null;
        $this->header = $header;
        $this->footer = $footer;
        $this->withoutHeader = $withoutHeader;
        $this->withoutFooter = $withoutFooter;
    }

    public function render()
    {
        return view('components.modal');
    }
}
