<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormSelect2Option extends Component
{
    public $label;
    public $placeholder;
    public $options;
    public $value;
    public $disableSearch;
    public $allowClear;
    public $closeOnSelect;
    public $withoutEmpty;
    public $horizontal;
    public $labelSize;
    public $inputSize;

    /**
     * label => String; optional
     * placeholder => String; optional
     * options => Array; optional
     * value => String; optional
     * disableSearch => Boolean; optional
     * allowClear => Boolean; optional
     * closeOnSelect => Boolean; optional
     * withoutEmpty => Boolean; optional
     * horizontal => Boolean; optional
     * labelSize => Integer; optional; (only if horizontal) 1 to 12
     * inputSize => Integer; optional; (only if horizontal) 1 to 12
     */
    public function __construct($label = null, $placeholder = null, $options = [], $value = null, $disableSearch = false, $allowClear = false, $closeOnSelect = true, $withoutEmpty = false, $horizontal = false, $labelSize = null)
    {
        $this->label = $label;
        $this->placeholder = $placeholder ?? (!empty($label) ? 'Pilih ' . strip_tags($label) : 'Pilih');
        $this->options = $options;
        $this->value = $value;
        $this->disableSearch = $disableSearch;
        $this->allowClear = $allowClear;
        $this->closeOnSelect = $closeOnSelect;
        $this->withoutEmpty = $withoutEmpty;
        $this->horizontal = $horizontal;
        $this->labelSize = !empty($label) ? $labelSize ?? 2 : 0;
        $this->inputSize = 12 - $this->labelSize;
    }

    public function render()
    {
        return view('components.form-select2-option');
    }
}
