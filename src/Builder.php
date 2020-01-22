<?php

namespace Larafun\Suite;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Larafun\Suite\Contracts\Queryable;

class Builder extends EloquentBuilder
{
    public function find($id, $columns = ['*'])
    {
        //we don't cache findMany - no use, will be called separately anyway
        if (is_array($id) || $id instanceof Arrayable)
            return $this->findMany($id, $columns);

        if (
            method_exists($this, 'getCacheTime') &&
            ! is_null($this->model->getCacheTime()) &&
            $this->model->getSingleModelCache()
        ) return $this->findCached($id, $columns);

        return $this->whereKey($id)->first($columns);
    }

    protected function findCached($id, $columns = ['*'])
    {
        $key = $this->model->getSingleCacheKey($id);
        $seconds = $this->model->getCacheTime();
        $cache = $this->model->getCache();

        $callback = $this->getCacheCallback($id, $columns);

        if ($seconds instanceof DateTime || $seconds > 0)
            return $cache->remember($key, $seconds, $callback);

        return $cache->rememberForever($key, $callback);
    }

    protected function getCacheCallback($id, $columns)
    {
        return function () use ($id, $columns) {
            $this->model->setCacheTime(null);
            return $this->find($id, $columns);
        };
    }

    public function get($columns = ['*'])
    {
        $collection = parent::get($columns);
        if ($collection instanceof Queryable) {
            $collection->setQuery($this->applyScopes()->getQuery());
        }
        return $collection;
    }

    public function __call($method, $parameters)
    {
        if (method_exists($this->model, $scope = 'realScope' . ucfirst($method))) {
            if (! $this->shouldCallRealScope($parameters)) {
                return $this;
            }
            return $this->callScope([$this->model, $scope], $parameters);
        }

        return parent::__call($method, $parameters);
    }

    protected function shouldCallRealScope($parameters)
    {
        foreach ($parameters as $parameter) {
            if (empty($parameter)) {
                return false;
            }
        }
        return true;
    }
}
