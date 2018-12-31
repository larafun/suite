<?php

namespace Larafun\Suite\Contracts\Collection;

use Illuminate\Database\Query\Builder;

interface QueryAware
{
    public function setQuery(Builder $query);

    public function query();
}
