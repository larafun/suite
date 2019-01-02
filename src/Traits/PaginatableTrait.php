<?php

namespace Larafun\Suite\Traits;

use Larafun\Suite\Contracts\Paginator;
use Larafun\Suite\Paginators\PaginatorFactory;

trait PaginatableTrait
{
    protected $paginator;

    public function setPaginator($paginator)
    {
        $this->paginator = $paginator;
        return $this;
    }

    public function getPaginator(): Paginator
    {
        return PaginatorFactory::make($this->paginator, $this);
    }
}