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
        factory(Book::class, 2)->create();
        $collection = Book::all();

        $response = $collection->toResponse(null);
        $presented = json_decode($response->getContent());

        $this->assertObjectHasAttribute('data', $presented);
        $this->assertCount(2, $presented->data);
    }
}
