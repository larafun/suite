<?php

namespace Larafun\Suite\Paginators;

use DB;
use Larafun\Suite\Contracts\Paginator;
use Larafun\Suite\Contracts\Queryable;
use Illuminate\Database\Query\Builder;

class QueryPaginator implements Paginator
{
    protected $query;

    protected $take = null;

    protected $skip = null;

    protected $count = null;

    protected $page = null;

    protected $size = null;

    protected $pages = null;

    public function __construct(Queryable $data)
    {
        $this->query = clone $data->getQuery();
    }

    public function page()
    {
        if (! is_null($this->page)) {
            return $this->page;
        }
        if (! $this->size()) {
            return 1;
        }
        return $this->page = (int) floor($this->skip() / $this->size()) + 1;
    }

    public function size()
    {
        return $this->take();
    }

    public function take()
    {
        if (! is_null($this->take)) {
            return $this->take;
        }
        return $this->take = (int) ($this->query->limit ?? PHP_INT_MAX);
    }

    public function skip()
    {
        if (! is_null($this->skip)) {
            return $this->skip;
        }
        return $this->skip = (int) ($this->query->offset ?? 0);
    }

    public function pages()
    {
        if (! is_null($this->pages)) {
            return $this->pages;
        }
        if (! $this->size()) {
            return $this->pages = 1;
        }
        return $this->pages = (int) floor($this->count() / $this->size()) + 1;
    }

    public function count()
    {
        if (! is_null($this->count)) {
            return $this->count;
        }
        $query = clone $this->query;
        $query->orders = null;  // no need to spend time ordering when counting results
        $query->skip(0)->take(PHP_INT_MAX); // eliminating existing offsets
        if (! $query->groups) {
            return $this->count = $query->count();
        }

        // the query has groups, so we need to count the total result set.
        return $this->count = DB::table(DB::raw("(" . $query->toSql() . ") as derived"))
            ->mergeBindings($query)
            ->count()
        ;
    }

    public function pagination(): array
    {
        return [
            'skip'  => $this->skip(),
            'take'  => $this->take(),
            'total' => $this->count()
        ];
    }
}
