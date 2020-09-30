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

        $pagination = (array) $presented->meta->pagination;

        $this->assertArrayHasKey('size', $pagination);
        $this->assertEquals(2, $pagination['size']);
        $this->assertArrayHasKey('page', $pagination);
        $this->assertEquals(2, $pagination['page']);
        $this->assertArrayHasKey('total', $pagination);
        $this->assertEquals(20, $pagination['total']);
        $this->assertArrayHasKey('total_pages', $pagination);
        $this->assertEquals(10, $pagination['total_pages']);
    }
}
