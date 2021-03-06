<?php

namespace Larafun\Suite\Paginators;

class CountPaginator extends QueryPaginator
{
    /**
     * The pagination information
     */
    public function pagination(): array
    {
        return [
            'page'  => $this->page(),
            'size'  => $this->size(),
            'total' => $this->count(),
            'total_pages'   => $this->pages(),
        ];
    }
}
