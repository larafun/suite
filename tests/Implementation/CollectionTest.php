<?php

namespace Larafun\Suite\Tests\Implementation;

use Larafun\Suite\Collection\ResourceableCollection;
use Larafun\Suite\Contracts\Queryable;
use Larafun\Suite\Contracts\Paginatable;
use Larafun\Suite\Tests\Models\Book;
use Larafun\Suite\Tests\TestCase;
use Illuminate\Contracts\Support\Responsable;

class CollectionTest extends TestCase
{
    /** @test */
    public function itUsesOurCollection()
    {
        $this->assertInstanceOf(ResourceableCollection::class, $this->collection());
    }

    /** @test */
    public function itIsQueryable()
    {
        $this->assertInstanceOf(Queryable::class, $this->collection());
    }

    /** @test */
    public function itIsPaginatable()
    {
        $this->assertInstanceOf(Paginatable::class, $this->collection());
    }

    /** @test */
    public function itIsResponsable()
    {
        $this->assertInstanceOf(Responsable::class, $this->collection());
    }

    protected function collection()
    {
        return (new Book())->newCollection([]);
    }
}
