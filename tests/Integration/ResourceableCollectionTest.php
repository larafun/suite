<?php

namespace Larafun\Suite\Tests\Integration;

use Illuminate\Database\Query\Builder;
use Larafun\Suite\Collection\ResourceableCollection;
use Larafun\Suite\Tests\DataTestCase;
use Larafun\Suite\Tests\Models\Book;
use Larafun\Suite\Contracts\Queryable;

class ResourceableCollectionTest extends DataTestCase
{
    /** @test */
    public function itLoadsTheCollection()
    {
        $collection = Book::all();
        $this->assertInstanceOf(ResourceableCollection::class, $collection);
    }

    /** @test */
    public function itIsAwareOfTheQuery()
    {
        $collection = Book::all();
        $this->assertInstanceOf(Queryable::class, $collection);
        $this->assertInstanceOf(Builder::class, $collection->getQuery());
    }

    /** @test */
    public function itPresentsTheCollection()
    {
        factory(Book::class, 20)->create();
        $collection = Book::take(2)->skip(3)->get();

        $response = $collection->toResponse(null);
        $presented = json_decode($response->getContent());

        // it presents the data
        $this->assertObjectHasAttribute('data', $presented);
        $this->assertCount(2, $presented->data);

        // it has the pagination
        $this->assertObjectHasAttribute('meta', $presented);
        $this->assertObjectHasAttribute('pagination', $presented->meta);

        $this->assertArraySubset([
                        'size'  => 2,
                        'page'  => 2,
                        'total' => 20,
                        'total_pages'   => 10
                    ], (array) $presented->meta->pagination);
    }
}
