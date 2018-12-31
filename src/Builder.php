<?php

namespace Larafun\Suite;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Larafun\Suite\Contracts\Collection\QueryAware;

class Builder extends EloquentBuilder
{

    public function get($columns = ['*'])
    {
        $collection = parent::get($columns);
        if ($collection instanceof QueryAware) {
            $collection->setQuery($this->query);
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
