<?php

namespace Larafun\Suite\Resources;

use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\ResourceCollection as LaravelResourceCollection;
use Larafun\Suite\Contracts\Resourceable;

class ResourceCollection extends LaravelResourceCollection
{
    public function __construct(Collection $resource)
    {
        $this->identifyCollects($resource);
        parent::__construct($resource);
    }

    protected function identifyCollects($collection)
    {
        $item = $collection->first();
        if ($item instanceof Resourceable) {
            $this->collects = $item->getResource();
        }
    }
}
