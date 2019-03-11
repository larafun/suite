<?php

namespace Larafun\Suite\Traits;

use Illuminate\Database\Query\Builder;

trait QueryableTrait
{
    protected $query;

    public function setQuery(Builder $query)
    {
        $this->query = $query;
        return $this;
    }

    public function getQuery(): Builder
    {
        return $this->query;
    }
}
