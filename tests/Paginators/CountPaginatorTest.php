<?php

namespace Larafun\Suite\Tests\Integration;

use Larafun\Suite\Tests\DataTestCase;
use Larafun\Suite\Tests\Models\Book;
use Larafun\Suite\Paginators\CountPaginator;

class CountPaginatorTest extends DataTestCase
{
    /** @test */
    public function itProperlyCounts()
    {
        Book::factory(20)->create();
        $collection = Book::skip(2)->take(3)->get();

        $collection->setPaginator(CountPaginator::class);

        $paginator = $collection->getPaginator();

        $this->assertEquals([
            'page'      => 1,
            'size'      => 3,
            'total'     => 20,
            'total_pages'   => 7
        ], $paginator->pagination());
    }

}
