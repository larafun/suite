<?php

namespace Larafun\Suite;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Larafun\Suite\Collection\PresentableCollection;
use Larafun\Suite\Contracts\Resourceable;
use Illuminate\Contracts\Support\Responsable;
use Larafun\Suite\Traits\ResourceableTrait;

abstract class Model extends EloquentModel implements Responsable, Resourceable
{
    use ResourceableTrait;

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
            config('suite.model.collection', PresentableCollection::class),
            ['items' => $models]
        );
    }

}
