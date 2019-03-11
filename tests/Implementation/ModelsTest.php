<?php

namespace Larafun\Suite\Tests\Implementation;

use Larafun\Suite\Builder;
use Larafun\Suite\Contracts\Resourceable;
use Illuminate\Contracts\Support\Responsable;
use Larafun\Suite\Tests\Models\Book;
use Larafun\Suite\Tests\TestCase;

class ModelsTest extends TestCase
{
    /** @test */
    public function itUsesOurBuilder()
    {
        $this->assertInstanceOf(Builder::class, $this->book()->newModelQuery());
    }

    /** @test */
    public function itIsResponsable()
    {
        $this->assertInstanceOf(Responsable::class, $this->book());
    }

    /** @test */
    public function itIsResourceable()
    {
        $this->assertInstanceOf(Resourceable::class, $this->book());
    }

    protected function book()
    {
        return new Book();
    }
}
