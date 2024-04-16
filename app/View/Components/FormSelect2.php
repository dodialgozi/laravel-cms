<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormSelect2 extends Component
{
    public $label;
    public $placeholder;
    public $url;
    public $key;
    public $value;
    public $objectValue;
    public $function;
    public $autoInit;
    public $useSelector;
    public $noScript;
    public $hashId;
    public $id;
    public $class;
    public $disableSearch;
    public $allowClear;
    public $tags;
    public $closeOnSelect;
    public $disabled;
    public $horizontal;
    public $labelSize;
    public $inputSize;

    /**
     * label => String; optional
     * placeholder => String; optional
     * url => String; required
     * key => String; optional; required if objectValue empty
     * value => String; optional; required if objectValue empty
     * objectValue => String; optional; ex: (obj)=>({id: obj.key, text: obj.value})
     * function => String; optional
     * autoInit => Boolean; optional
     * useSelector => Boolean; optional
     * noScript => Boolean; optional
     * hashId => String; optional
     * id => String; optional
     * class => String; optional
     * disableSearch => Boolean; optional
     * allowClear => Boolean; optional
     * closeOnSelect => Boolean; optional
     * disabled => Boolean; optional
     * horizontal => Boolean; optional
     * labelSize => Integer; optional; (only if horizontal) 1 to 12
     * inputSize => Integer; optional; (only if horizontal) 1 to 12
     */
    public function __construct($url, $label = null, $placeholder = null, $key = null, $value = null, $objectValue = null, $function = null, $autoInit = true, $useSelector = false, $noScript = false, $hashId = null, $id = null, $class = null, $disableSearch = false, $allowClear = false, $tags = false, $closeOnSelect = true, $disabled = false, $horizontal = false, $labelSize = null)
    {
        $this->label = $label;
        $this->placeholder = $placeholder ?? (!empty($label) ? 'Pilih ' . strip_tags($label) : 'Pilih');
        $this->url = $url;
        $this->key = $key;
        $this->value = $value;
        $this->objectValue = $objectValue;
        $this->function = $function;
        $this->autoInit = $autoInit;
        $this->useSelector = $useSelector;
        $this->noScript = $noScript;
        $this->hashId = $hashId;
        $this->id = $id;
        $this->class = $class;
        $this->disableSearch = $disableSearch;
        $this->allowClear = $allowClear;
        $this->tags = $tags;
        $this->closeOnSelect = $closeOnSelect;
        $this->disabled = $disabled;
        $this->horizontal = $horizontal;
        $this->labelSize = !empty($label) ? $labelSize ?? 2 : 0;
        $this->inputSize = 12 - $this->labelSize;
    }

    public function render()
    {
        return view('components.form-select2');
    }
}
