<?php

namespace Larafun\Suite\Tests\Presenters;

use Larafun\Suite\Contracts\Presenter as PresenterInterface;
use Larafun\Suite\Contracts\Transformable;
use Larafun\Suite\Presenters\Presenter;
use Larafun\Suite\Traits\TransformableTrait;
use Larafun\Suite\Transformers\PlainTransformer;
use Larafun\Suite\Tests\TestCase;

class PresenterTest extends TestCase
{
    /** @test */
    public function itImplementsTheInterface()
    {
        $presenter = new Presenter();
        $this->assertInstanceOf(PresenterInterface::class, $presenter);
    }

    /** @test */
    public function itMaintainsTheStructure()
    {
        $presenter = new Presenter();
        $this->assertTrue(is_array($presenter->present()));
        $this->assertEquals(['data', 'meta'], array_keys($presenter->present()));
    }

    /** @test */
    public function itSetsTheTransformer()
    {
        $presenter = new Presenter();
        $presenter->setTransformer(FirstTransformerStub::class);
        $this->assertEquals(FirstTransformerStub::class, $presenter->getTransformer());
    }

    /** @test */
    public function itBootsTheTransformer()
    {
        $transformer = $this->getMockBuilder(FirstTransformerStub::class)->getMock();
        $transformer->expects($this->once())->method('boot');

        $presenter = new Presenter();
        $presenter->setTransformer($transformer);
        $presenter->present();
    }

    /** @test */
    public function presenterTransformerHasPriority()
    {
        $data = new DataStub();
        $data->setTransformer(FirstTransformerStub::class);

        $presenter = new Presenter($data);
        $presenter->setTransformer(SecondTransformerStub::class);

        $this->assertEquals(SecondTransformerStub::class, $presenter->getTransformer());
    }

    /** @test */
    public function itFallsbackToDataTransformer()
    {
        $data = new DataStub();
        $data->setTransformer(FirstTransformerStub::class);

        $presenter = new Presenter($data);

        $this->assertEquals(FirstTransformerStub::class, $presenter->getTransformer());
    }
}

class FirstTransformerStub extends PlainTransformer
{
    
}

class SecondTransformerStub extends PlainTransformer
{

}

class DataStub implements Transformable
{
    use TransformableTrait;
}