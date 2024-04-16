<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Table extends Component
{
    public $columns;
    public $models;
    public $mainURL;
    public $action;
    public $rowAttributes;
    public $primaryKey;
    public $useNumber;
    public $useCheckbox;
    public $globalSearch;
    public $exclude;
    public $noWrap;

    /**
     * columns => Array; required; array of config column
     * models => Array or Collection; required
     * mainURL => String; required
     * action => Function($model); return String of HTML of action button; optional;
     * rowAttributes => Function($model); return Array; optional
     * primaryKey => String; optional
     * useNumber => Boolean; optional
     * useCheckbox => Boolean; optional
     * globalSearch => Boolean; optional
     * exclude => Boolean Function($item); optional; Function that return Boolean; return true if exluded
     * noWrap => Boolean; optional
     */
    public function __construct($columns, $models, $mainURL, $action = null, $rowAttributes = null, $primaryKey = null, $useNumber = true, $useCheckbox = false, $globalSearch = false, $exclude = null, $noWrap = true)
    {
        $this->columns = $columns;
        $this->models = $models;
        $this->mainURL = $mainURL;
        $this->action = $action;
        $this->rowAttributes = $rowAttributes;
        $this->primaryKey = $primaryKey;
        $this->useNumber = $useNumber;
        $this->useCheckbox = $useCheckbox;
        $this->globalSearch = $globalSearch;
        $this->exclude = $exclude;
        $this->noWrap = $noWrap;
    }

    public function render()
    {
        return view('components.table');
    }
}
