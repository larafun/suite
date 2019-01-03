<?php

namespace Larafun\Suite;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Larafun\Suite\Collection\PresentableCollection;
use Larafun\Suite\Contracts\Presentable;
use Larafun\Suite\Contracts\Transformable;
use Larafun\Suite\Traits\PresentableTrait;
use Larafun\Suite\Traits\TransformableTrait;

abstract class Model extends EloquentModel implements Presentable, Transformable
{
    use PresentableTrait, TransformableTrait;

    public function newEloquentBuilder($query)
    {
        return app(
            config('suite.model.collection', Builder::class),
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

    public function toJson($options = 0)
    {
        return $this->getPresenter()->toJson($options);
    }

}
