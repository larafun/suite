<?php

namespace Larafun\Suite\Transformers;

use Larafun\Suite\Contracts\Transformer;

class TransformerFactory
{
    public static function make($transformer = null, $data = null): Transformer
    {
        if (empty($transformer)) {
            $transformer = PlainTransformer::class;
        }
        if (is_string($transformer)) {
            $transformer = app($transformer, compact('data'));
        }
        return $transformer;
    }
}