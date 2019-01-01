<?php

namespace Larafun\Suite\Traits;

use Illuminate\Support\Collection;
use Larafun\Suite\Contracts\Transformable;

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
        if ($this->transformer) {
            return $this->transformer;
        }

        if (  ($this instanceof Collection)
            && (($first = $this->first()) instanceof Transformable)
        ){
            return $first->getTransformer();
        }

        return null;
    }
}