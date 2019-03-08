<?php

namespace Larafun\Suite\Collection;

use Illuminate\Database\Eloquent\Collection;
use Larafun\Suite\Contracts\Queryable;
use Larafun\Suite\Traits\QueryableTrait;
use Larafun\Suite\Traits\PaginatableTrait;
use Illuminate\Contracts\Support\Responsable;
use Larafun\Suite\Resources\CollectionResource;
use Larafun\Suite\Resources\Resource;
use Larafun\Suite\Paginators\QueryPaginator;
use App\Http\Resources\Book;

class PresentableCollection extends Collection implements
    Queryable,
    Responsable
{
    use QueryableTrait, PaginatableTrait;

    public function toResponse($request)
    {
        return Resource::collection($this)
            ->additional([
                'meta' => [
                    'pagination' => (new QueryPaginator($this))->pagination()
                ]
            ])
            ->response()
        ;
    }
}
