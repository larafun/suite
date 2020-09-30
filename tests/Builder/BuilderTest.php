<?php

namespace Larafun\Suite\Tests\Integration;

use DB;
use Larafun\Suite\Tests\Models\Book;
use Larafun\Suite\Tests\DataTestCase;
use Larafun\Suite\Collection\ResourceableCollection;

class BuilderTest extends DataTestCase
{
    /** @test */
    public function itProperlyCounts()
    {
        Book::factory(20)->create();
        $resource = DB::table('test_books')->skip(2)->take(3)->resource();

        $this->assertInstanceOf(ResourceableCollection::class, $resource);
    }

}
