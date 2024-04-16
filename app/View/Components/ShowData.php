<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ShowData extends Component
{
    public $label;
    public $type;
    public $value;
    public $values;
    public $latitude;
    public $longitude;
    public $height;

    /**
     * label => String; optional
     * type => String; optional; ('doc', 'doc-thumb', 'image', 'images', 'newline', 'map', 'html', 'json')
     * value => String; optional
     * values => Array of String; optional; only when type is 'images'
     * latitude => Decimal; optional; only when type is 'map'
     * longitude => Decimal; optional; only when type is 'map'
     * height => String; optional; only when type is 'map'; ex: 450px
     */
    public function __construct($label = null, $type = null, $value = null, $values = [], $latitude = null, $longitude = null, $height = '450px')
    {
        $this->label = $label ?? '';
        $this->type = $type;
        $this->value = $value ?? '';
        $this->values = $values;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->height = $height;
    }

    public function render()
    {
        return view('components.show-data');
    }
}
