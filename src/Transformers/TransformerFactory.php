<?php

namespace Larafun\Suite\Transformers;

use Larafun\Suite\Contracts\Transformer;

class TransformerFactory
{
    public static function make($instance = null, $data = null): Transformer
    {
        if (empty($instance)) {
            $instance = PlainTransformer::class;
        }
        if (is_string($instance)) {
            $transformer = app($instance, compact('data'));
        }
        return $transformer;
    }
}