<?php

namespace Larafun\Suite\Tests;

use Larafun\Suite\Tests\Models\Book;

class ExampleTest extends DataTestCase
{
    public function testExample()
    {
        factory(Book::class, 20)->create();
        $this->assertCount(20, Book::all());
    }
}
