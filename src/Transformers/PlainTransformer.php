<?php

namespace Larafun\Suite\Transformers;

class PlainTransformer extends AbstractTransformer
{
    public function transform($data)
    {
        return $data;
    }
}