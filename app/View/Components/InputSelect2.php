<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputSelect2 extends Component
{
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
    public $placeholder;
    public $disableSearch;
    public $allowClear;
    public $tags;
    public $closeOnSelect;

    /**
     * url => String; required
     * key => String; optional; required if using value or using hashId
     * value => String; optional; required if objectValue empty
     * objectValue => String; optional; ex: (obj)=>({id: obj.key, text: obj.value})
     * function => String; optional
     * autoInit => Boolean; optional
     * useSelector => Boolean; optional
     * noScript => Boolean; optional
     * hashId => String; optional
     * id => String; optional
     * class => String; optional
     * placeholder => String; optional
     * disableSearch => Boolean; optional
     * allowClear => Boolean; optional
     * closeOnSelect => Boolean; optional
     */
    public function __construct($url, $key = null, $value = null, $objectValue = null, $function = null, $autoInit = true, $useSelector = false, $noScript = false, $hashId = null, $id = null, $class = null, $placeholder = '', $disableSearch = false, $allowClear = false, $tags = false, $closeOnSelect = true)
    {
        $this->url = $url;
        $this->key = $key;
        $this->value = $value;
        $this->objectValue = $objectValue;
        $this->function = $function ?? 'load' . strtoupper(randomGenAlpha(1)) . randomGen2(15);
        $this->autoInit = $autoInit;
        $this->useSelector = $useSelector;
        $this->noScript = $noScript;
        $this->hashId = $hashId;
        $this->id = $id;
        $this->class = $class;
        $this->placeholder = $placeholder;
        $this->disableSearch = $disableSearch;
        $this->allowClear = $allowClear;
        $this->tags = $tags;
        $this->closeOnSelect = $closeOnSelect;
    }

    public function render()
    {
        return view('components.input-select2');
    }
}
