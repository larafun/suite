<?php

namespace Larafun\Suite\Traits;

trait TransformableTrait
{
    protected $transformer;

    public function setTransformer($transformer)
    {
        $this->transformer = $transformer;
        return $this;
    }

    public function getTransformer()
    {
        return $this->transformer;
    }
}