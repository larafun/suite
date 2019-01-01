<?php

namespace Larafun\Suite\Tests;

use Larafun\Suite\Tests\Models\Book;

class ExampleTest extends DataTestCase
{
    /**
     * Tests that need to be covered: 
     *  - Model transformer has precedence over the toArray method
     *  - Collection transformer has precendence over the Model transformer
     *  - Presenter transformer has precendence over the Data transformer
     */
    public function testExample()
    {
        factory(Book::class, 20)->create();
        $this->assertCount(20, Book::all());
    }
}
