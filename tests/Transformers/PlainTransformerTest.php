<?php

namespace Larafun\Suite\Tests\Transformers;

use Larafun\Suite\Contracts\Transformer as TransformerInterface;
use Larafun\Suite\Transformers\PlainTransformer;
use Larafun\Suite\Tests\TestCase;

class PlainTransformerTest extends TestCase
{
    /** @test */
    public function itImplementsTheInterface()
    {
        $transformer = new PlainTransformer();
        $this->assertInstanceOf(TransformerInterface::class, $transformer);
    }

    /** @test */
    public function itDoesNotAlterTheData()
    {
        $data = new \StdClass();
        $transformer = new PlainTransformer();
        $transformer->boot();
        $this->assertEquals($data, $transformer->transform($data));
    }
}