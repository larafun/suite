<?php

namespace Larafun\Suite\Filters;

abstract class AbstractFilter
{
    protected $attributes = [];

    public function __construct($attributes = [])
    {
        if (empty($attributes)) {
            $attributes = request()->all();
        }
        $this->attributes = array_merge(
            $this->defaults(),
            $attributes
        );
    }

    abstract public function defaults(): array;

    public function __get($key)
    {
        if (!array_key_exists($key, $this->attributes)) {
            return null;
        }
        return $this->attributes[$key];
    }
}