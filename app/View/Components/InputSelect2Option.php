<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputSelect2Option extends Component
{
    public $options;
    public $value;
    public $placeholder;
    public $disableSearch;
    public $allowClear;
    public $closeOnSelect;
    public $withoutEmpty;

    /**
     * options => Array; optional
     * value => String; optional
     * placeholder => String; optional
     * disableSearch => Boolean; optional
     * allowClear => Boolean; optional
     * closeOnSelect => Boolean; optional
     * withoutEmpty => Boolean; optional
     */
    public function __construct($options = [], $value = null, $placeholder = 'Pilih', $disableSearch = false, $allowClear = false, $closeOnSelect = true, $withoutEmpty = false)
    {
        $this->options = $options;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->disableSearch = $disableSearch;
        $this->allowClear = $allowClear;
        $this->closeOnSelect = $closeOnSelect;
        $this->withoutEmpty = $withoutEmpty;
    }

    public function render()
    {
        return view('components.input-select2-option');
    }
}
