<?php

namespace Larafun\Suite;

use Larafun\Suite\Collection\PresentableCollection;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    public function newCollection(array $models = [])
    {
        return new PresentableCollection($models);
    }
}
