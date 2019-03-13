<?php

namespace Larafun\Suite\Paginators;

use Illuminate\Support\Collection;
use Larafun\Suite\Contracts\Paginator;
use Larafun\Suite\Contracts\Queryable;

class PaginatorFactory
{
    public static function make($paginator = null, $data = null): Paginator
    {
        if (empty($paginator)) {
            $paginator = self::getDefaultPaginator($data);
        }
        if (is_string($paginator)) {
            $paginator = app($paginator, compact('data'));
        }
        return $paginator;
    }

    protected static function getDefaultPaginator($data = null)
    {
        if (($data instanceof Collection) && ($data instanceof Queryable)) {
            return config('suite.collection.paginator', CountPaginator::class);
        }
        return NullPaginator::class;
    }
}
