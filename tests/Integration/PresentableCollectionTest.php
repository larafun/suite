<?php

namespace Larafun\Suite\Tests\Integration;

use Illuminate\Database\Query\Builder;
use Larafun\Suite\Collection\PresentableCollection;
use Larafun\Suite\Tests\DataTestCase;
use Larafun\Suite\Tests\Models\Book;
use Larafun\Suite\Tests\Models\TransformedBook;
use Larafun\Suite\Tests\Stubs\DataStub;
use Larafun\Suite\Tests\Stubs\FirstTransformerStub;
use Larafun\Suite\Tests\Stubs\SecondTransformerStub;

class PresentableCollectionTest extends DataTestCase
{
    /** @test */
    public function itLoadsTheCollection()
    {
        $collection = Book::all();
        $this->assertInstanceOf(PresentableCollection::class, $collection);
    }

    /** @test */
    public function itIsAwareOfTheQuery()
    {
        $collection = Book::all();
        $this->assertInstanceOf(Builder::class, $collection->getQuery());
    }

    /** @test */
    public function itPresentsTheCollection()
    {
        factory(Book::class, 2)->create();
        $collection = Book::all();
        $presented = json_decode($collection->toJson());

        $this->assertObjectHasAttribute('data', $presented);
        $this->assertCount(2, $presented->data);
    }
    

    /** @test */
    public function ownTransformerHasPriority()
    {
        factory(Book::class, 2)->create();

        $collection = TransformedBook::all();
        $collection->setTransformer(SecondTransformerStub::class);

        $this->assertEquals(SecondTransformerStub::class, $collection->getTransformer());
    }

    /** @test */
    public function itFallsbackToModelTransformer()
    {
        factory(Book::class, 2)->create();

        $collection = TransformedBook::all();

        $this->assertEquals(FirstTransformerStub::class, $collection->getTransformer());
    } 
}