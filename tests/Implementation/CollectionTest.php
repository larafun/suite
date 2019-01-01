<?php

namespace Larafun\Suite\Tests\Implementation;

use Larafun\Suite\Builder;
use Larafun\Suite\Collection\PresentableCollection;
use Larafun\Suite\Contracts\Queryable;
use Larafun\Suite\Contracts\Presentable;
use Larafun\Suite\Contracts\Transformable;
use Larafun\Suite\Tests\Models\Book;
use Larafun\Suite\Tests\TestCase;

class CollectionTest extends TestCase
{
    /** @test */
    public function itUsesOurCollection()
    {
        $this->assertInstanceOf(PresentableCollection::class, $this->collection());
    }

    /** @test */
    public function itIsPresentable()
    {
        $this->assertInstanceOf(Presentable::class, $this->collection());
    }

    /** @test */
    public function itIsTransformable()
    {
        $this->assertInstanceOf(Transformable::class, $this->collection());
    }

    /** @test */
    public function itIsQueryable()
    {
        $this->assertInstanceOf(Queryable::class, $this->collection());
    }

    protected function collection()
    {
        return (new Book())->newCollection([]);
    }
}
