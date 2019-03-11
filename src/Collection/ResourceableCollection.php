<?php

namespace Larafun\Suite\Collection;

use Illuminate\Database\Eloquent\Collection;
use Larafun\Suite\Contracts\Queryable;
use Larafun\Suite\Contracts\Resourceable;
use Larafun\Suite\Contracts\Paginatable;
use Larafun\Suite\Traits\QueryableTrait;
use Larafun\Suite\Traits\PaginatableTrait;
use Larafun\Suite\Traits\ResourceableTrait;
use Larafun\Suite\Traits\InterceptedTrait;
use Illuminate\Contracts\Support\Responsable;

class ResourceableCollection extends Collection implements
    Queryable,
    Responsable,
    Paginatable
{
    use QueryableTrait, PaginatableTrait, ResourceableTrait, InterceptedTrait;

    public function toResponse($request)
    {
        return $this->resource()
            ->response()
        ;
    }

    public function resource()
    {
        if (! $this->resource) {
            $class = $this->getResource();
            $this->resource = $class::collection($this);
        }
        return $this->resource;
    }

    public function getResource()
    {
        $first = $this->first();
        if ($first instanceof Resourceable) {
            return $first->getResource();
        }
        return PlainResource::class;
    }

    public function keyBy($keyBy)
    {
        return $this->toBase()->keyBy($keyBy);
    }
}