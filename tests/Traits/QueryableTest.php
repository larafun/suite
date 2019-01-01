<?php

namespace Larafun\Suite\Tests\Traits;

use Larafun\Suite\Tests\TestCase;
use Larafun\Suite\Traits\QueryableTrait;

class QueryableTest extends TestCase
{
    /** @test */
    public function itCanUseMethods()
    {
        $stub = new QueryableStub();
        $query = \DB::query();

        $stub->setQuery($query);
        $this->assertEquals($query, $stub->getQuery());
    }
}

class QueryableStub
{
    use QueryableTrait;
}