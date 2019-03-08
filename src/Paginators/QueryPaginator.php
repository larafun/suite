<?php

namespace Larafun\Suite\Paginators;

use DB;
use Larafun\Suite\Contracts\Queryable;
use Larafun\Suite\Contracts\Paginator;

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
            'total' => (int) $this->getCount($query)
        ];
    }

    protected function getCount($query)
    {
        $query->orders = null;  // no need to spend time ordering when counting results
        $query->skip(0)->take(PHP_INT_MAX); // eliminating existing offsets
        if (! $query->groups) {
            return $query->count();
        }

        // the query has groups, so we need to count the total result set.
        return DB::table(DB::raw("(" . $query->toSql() . ") as derived"))
            ->mergeBindings($query)
            ->count()
        ;
    }
}
