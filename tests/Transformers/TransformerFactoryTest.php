<?php

namespace Larafun\Suite\Tests\Transformers;

use Larafun\Suite\Contracts\Transformer as TransformerInterface;
use Larafun\Suite\Transformers\Transformer;
use Larafun\Suite\Transformers\PlainTransformer;
use Larafun\Suite\Transformers\TransformerFactory;
use Larafun\Suite\Tests\TestCase;

class TransformerFactoryTest extends TestCase
{
    /** @test */
    public function itCanUseTheDefault()
    {
        $transformer = TransformerFactory::make();
        $this->assertInstanceOf(PlainTransformer::class, $transformer);        
    }

    /** @test */
    public function itCanCreateTransformer()
    {
        $transformer = TransformerFactory::make(Transformer::class);
        $this->assertInstanceOf(TransformerInterface::class, $transformer);        
    }

    /** @test */
    public function itCanPassData()
    {
        $data = new \StdClass();

        $transformer = TransformerFactory::make(ExtendedTransformer::class, $data);
        $this->assertEquals($data, $transformer->data());
    }

    /** @test */
    public function itCanBuildWithoutData()
    {
        $transformer = TransformerFactory::make(ExtendedTransformer::class);
        $this->assertNull($transformer->data());
    }
}

class ExtendedTransformer extends Transformer
{
    public function data()
    {
        return $this->data;
    }
}