<?php

namespace Larafun\Suite\Contracts;

interface OffsetPaginator extends Paginator
{

    /**
     * Compute the current page number
     */
    public function page();
    
    /**
     * Alias for take
     */
    public function size();

    /**
     * The number of results that has been requested from the database
     */
    public function take();

    /**
     * The number of results that were skipped from the database
     */
    public function skip();

    /**
     * The total number of pages
     */
    public function pages();
}
