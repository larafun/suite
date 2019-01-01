<?php

namespace Larafun\Suite\Tests\Traits;

use Larafun\Suite\Traits\TransformableTrait;
use Larafun\Suite\Transformers\Transformer;
use Larafun\Suite\Tests\TestCase;

class TransformableTest extends TestCase
{
    /** @test */
    public function itCanUseMethods()
    {
        $stub = new TransformableStub();
        $stub->setTransformer(Transformer::class);
        $this->assertEquals(Transformer::class, $stub->getTransformer());

        $transformer = new Transformer($stub);
        $stub->setTransformer($transformer);
        $this->assertEquals($transformer, $stub->getTransformer());
    }
}

class TransformableStub
{
    use TransformableTrait;
}