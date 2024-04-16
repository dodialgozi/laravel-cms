<?php

namespace App\Models\Traits;

trait ModelFunction
{
    private static $callableMethods = ['findOrNull'];

    private function __findOrNull($id)
    {
        return !empty($id) ? $this->find($id) : null;
    }

    public function __call($method, $parameters)
    {
        if (in_array($method, static::$callableMethods)) {
            return $this->{"__{$method}"}(...$parameters);
        }

        return parent::__call($method, $parameters);
    }

    public static function __callStatic($method, $parameters)
    {
        if (in_array($method, static::$callableMethods)) {
            return (new static)->{"__{$method}"}(...$parameters);
        }

        return parent::__callStatic($method, $parameters);
    }
}
