<?php

namespace Larafun\Suite\Paginators;

use Larafun\Suite\Contracts\Queryable;
use Larafun\Suite\Contracts\Paginator;
use Illuminate\Database\Query\Builder;

class QueryPaginator implements Paginator
{
    protected $query;

    public function __construct(Queryable $data)
    {
        $this->query = clone $data->getQuery();
    }

    public function pagination(): array
    {
        $query = clone $this->query;
        return [
            'skip'  => (int) ($query->offset ?? 0),
            'take'  => (int) ($query->limit ?? PHP_INT_MAX),
            'total' => (int) $query->skip(0)->take(PHP_INT_MAX)->count(),
        ];
    }
}
