<?php

namespace Larafun\Suite\Traits;

use Larafun\Suite\Builder;
use Larafun\Suite\Collection\ResourceableCollection;

trait SuitableTrait
{

    public function newEloquentBuilder($query)
    {
        return app(
            config('suite.model.builder', Builder::class),
            compact('query')
        );
    }

    public function newCollection(array $models = [])
    {
        return app(
            config('suite.model.collection', ResourceableCollection::class),
            ['items' => $models]
        );
    }
}
