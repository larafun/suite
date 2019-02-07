<?php

namespace Larafun\Suite\Transformers;

use Larafun\Suite\Contracts\Transformer;
use Illuminate\Support\Collection;

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

    protected function collect($data): Collection
    {
        if ($data instanceof Collection) {
            return $data;
        }
        return collect($data);
    }
}
