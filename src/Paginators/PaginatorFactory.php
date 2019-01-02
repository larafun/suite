<?php

namespace Larafun\Suite\Paginators;

use Larafun\Suite\Contracts\Paginator;

class PaginatorFactory
{
    public static function make($paginator = null, $data = null): Paginator
    {
        if (is_string($paginator)) {
            $paginator = app($paginator, compact('data'));
        }
        return $paginator;
    }
}