<?php

namespace Larafun\Suite;

use DateTime;

class QueryBuilder extends \Illuminate\Database\Query\Builder
{
    protected $model;

    /**
     * Execute the query as a "select" statement.
     *
     * @param  array $columns
     * @return array|\Illuminate\Support\Collection
     */
    public function get($columns = ['*'])
    {
        if (! is_null($this->model->getCacheTime()) && empty($this->model->getSingleModelCache()))
            return $this->getCached($columns);

        return parent::get($columns);
    }

    /**
     * Execute the query as a cached "select" statement.
     *
     * @param  array  $columns
     * @return array
     */
    protected function getCached($columns = ['*'])
    {
        if (is_null($this->columns))
            $this->columns = $columns;

        // If the query is requested to be cached, we will cache it using a unique key
        // for this database connection and query statement, including the bindings
        // that are used on this query, providing great convenience when caching.
        $key = $this->model->getCacheKey();
        $seconds = $this->model->getCacheTime();
        $cache = $this->model->getCache();

        $callback = $this->getCacheCallback($columns);

        // If we've been given a DateTime instance or a "seconds" value that is
        // greater than zero then we'll pass it on to the remember method.
        // Otherwise we'll cache it indefinitely.
        if ($seconds instanceof DateTime || $seconds > 0)
            return $cache->remember($key, $seconds, $callback);

        return $cache->rememberForever($key, $callback);
    }

    /**
     * Get the Closure callback used when caching queries.
     *
     * @param  array  $columns
     * @return \Closure
     */
    protected function getCacheCallback($columns)
    {
        return function () use ($columns) {
            $this->model->setCacheTime(null);
            return $this->get($columns);
        };
    }

    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }

}