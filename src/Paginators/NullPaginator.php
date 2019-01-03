<?php

namespace Larafun\Suite\Paginators;

use Larafun\Suite\Contracts\Queryable;
use Larafun\Suite\Contracts\Paginator;
use Illuminate\Database\Query\Builder;

class NullPaginator implements Paginator
{
    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function pagination(): array
    {
        return [];
    }
}