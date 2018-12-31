<?php

namespace Larafun\Suite\Transformers;

use Larafun\Suite\Contracts\Transformer;

abstract class AbstractTransformer implements Transformer
{
    protected $data;

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function boot()
    {
        // implement behaviour before transforming entitites
    }
}