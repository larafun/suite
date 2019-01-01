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
        return new Builder($query);
    }

    public function newCollection(array $models = [])
    {
        return new PresentableCollection($models);
    }
}
