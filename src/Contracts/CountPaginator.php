<?php

namespace Larafun\Suite\Contracts;

interface CountPaginator extends OffsetPaginator
{
    /**
     * The total number of results in the database
     */
    public function count();
}
