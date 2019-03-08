<?php

namespace Larafun\Suite\Paginators;

use Larafun\Suite\Contracts\Paginator;

class NullPaginator implements Paginator
{
    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function pagination(): array
    {
        return [];
    }
}
