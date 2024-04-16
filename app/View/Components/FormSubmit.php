<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormSubmit extends Component
{
    public $submit;
    public $type;
    public $icon;
    public $back;
    public $hideBack;
    public $hideSubmit;

    /**
     * submit => String; optional
     * type => String; optional; ('primary', 'info', 'success', 'warning', 'danger')
     * icon => String; optional
     * back => String; optional
     * hideBack => Boolean; optional
     * hideSubmit => Boolean; optional
     */
    public function __construct($submit = 'Simpan', $type = 'primary', $icon = 'fas fa-check', $back = 'Batal', $hideBack = false, $hideSubmit = false)
    {
        $this->submit = $submit;
        $this->type = $type;
        $this->icon = $icon;
        $this->back = $back;
        $this->hideBack = $hideBack;
        $this->hideSubmit = $hideSubmit;
    }

    public function render()
    {
        return view('components.form-submit');
    }
}
