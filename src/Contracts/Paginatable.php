<?php

namespace Larafun\Suite\Contracts;

use Larafun\Suite\Contracts\Paginator;

interface Paginatable
{
    public function setPaginator($paginator);

    public function getPaginator(): Paginator;
}