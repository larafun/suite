<?php

namespace Larafun\Suite\Tests;

use Larafun\Suite\Tests\Models\Book;

class ExampleTest extends DataTestCase
{
    public function testExample()
    {
        Book::factory(20)->create();
        $this->assertCount(20, Book::all());
    }
}
