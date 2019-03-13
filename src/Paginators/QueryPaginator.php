<?php

namespace Larafun\Suite\Paginators;

use DB;
use Larafun\Suite\Contracts\CountPaginator;
use Larafun\Suite\Contracts\Queryable;

class QueryPaginator implements CountPaginator
{
    /**
     * The current database results
     */
    protected $data;

    /**
     * The query that was performed to collect the data
     * and that will be rebuilt in order to count the
     * number of records from the database
     */
    protected $query;

    /**
     * The number of results that have been requested
     * from the database
     */
    protected $take = null;

    /**
     * The number of results that have been skiped
     * from the database
     */
    protected $skip = null;

    /**
     * The total number of records in database
     */
    protected $count = null;

    /**
     * The current page number
     */
    protected $page = null;

    /**
     * The total number of pages
     */
    protected $pages = null;


    public function __construct(Queryable $data)
    {
        $this->data = $data;
        $this->query = clone $data->getQuery();
    }

    /**
     * Compute the current page number
     */
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

    /**
     * Alias for take
     */
    public function size()
    {
        return $this->take();
    }

    /**
     * The number of results that has been requested from the database
     */
    public function take()
    {
        if (! is_null($this->take)) {
            return $this->take;
        }
        return $this->take = (int) ($this->query->limit ?? PHP_INT_MAX);
    }

    /**
     * The number of results that were skipped from the database
     */
    public function skip()
    {
        if (! is_null($this->skip)) {
            return $this->skip;
        }
        return $this->skip = (int) ($this->query->offset ?? 0);
    }

    /**
     * The total number of pages
     */
    public function pages()
    {
        if (! is_null($this->pages)) {
            return $this->pages;
        }
        if (! $this->size()) {
            return $this->pages = 1;
        }
        return $this->pages = (int) ceil($this->count() / $this->size());
    }

    /**
     * The total number of results in the database
     */
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

    /**
     * The pagination information
     */
    public function pagination(): array
    {
        return [
            'skip'  => $this->skip(),
            'take'  => $this->take(),
            'total' => $this->count()
        ];
    }
}
