<?php

namespace Larafun\Suite\Tests\Filters;

use Larafun\Suite\Tests\Stubs\FilterStub;
use Larafun\Suite\Tests\TestCase;
use Larafun\Suite\Filters\AbstractFilter;

class FilterTest extends TestCase
{
    /** @test */
    public function itPassesValidation()
    {
        $filter = new FilterStub(['foo' => 'bar']);
        $this->assertInstanceOf(AbstractFilter::class, $filter);
    }

    /** @test */
    public function itFailsValidation()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $filter = new FilterStub(['bar' => 'baz']);
        $this->assertInstanceOf(AbstractFilter::class, $filter);
    }
}
