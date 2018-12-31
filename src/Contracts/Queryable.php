<?php

namespace Larafun\Suite\Contracts;

use Illuminate\Database\Query\Builder;

interface Queryable
{
    public function setQuery(Builder $query);

    public function getQuery();
}
