<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputTimepicker extends Component
{
    public $id;
    public $groupId;
    public $function;
    public $autoInit;
    public $useSelector;
    public $noScript;

    /**
     * id => String; optional
     * groupId => String; optional
     * function => String; optional
     * autoInit => Boolean; optional
     * useSelector => Boolean; optional
     * noScript => Boolean; optional
     */
    public function __construct($id = null, $groupId = null, $function = null, $autoInit = true, $useSelector = false, $noScript = false)
    {
        $this->id = $id ?? (!$noScript && !$useSelector ? randomGen2(16) : null);
        $this->groupId = $groupId;
        $this->function = $function ?? 'load' . strtoupper(randomGenAlpha(1)) . randomGen2(15);
        $this->autoInit = $autoInit;
        $this->useSelector = $useSelector;
        $this->noScript = $noScript;
    }

    public function render()
    {
        return view('components.input-timepicker');
    }
}
