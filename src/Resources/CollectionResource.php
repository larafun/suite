<?php

namespace Larafun\Suite\Resources;

use Larafun\Suite\Paginators\QueryPaginator;

class CollectionResource extends Resource
{
    public function with($request) {
        return [
            'meta' => [
                'foo'   => 'bar',
                'pagination' => (new QueryPaginator($this->resource))->pagination()
            ]
        ];
    }
}
