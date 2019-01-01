<?php

namespace Larafun\Suite\Tests\Implementation;

use Larafun\Suite\Builder;
use Larafun\Suite\Contracts\Presentable;
use Larafun\Suite\Contracts\Transformable;
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
    public function itIsPresentable()
    {
        $this->assertInstanceOf(Presentable::class, $this->book());
    }

    /** @test */
    public function itIsTransformable()
    {
        $this->assertInstanceOf(Transformable::class, $this->book());
    }

    protected function book()
    {
        return new Book();
    }
}
