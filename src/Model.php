<?php

namespace Larafun\Suite;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Larafun\Suite\Contracts\Resourceable;
use Illuminate\Contracts\Support\Responsable;
use Larafun\Suite\Traits\ResourceableTrait;
use Larafun\Suite\Traits\SuitableTrait;

abstract class Model extends EloquentModel implements Responsable, Resourceable
{
    use ResourceableTrait, SuitableTrait;
    
}
