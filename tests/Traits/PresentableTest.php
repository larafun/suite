<?php

namespace Larafun\Suite\Tests\Traits;

use Larafun\Suite\Presenters\Presenter;
use Larafun\Suite\Presenters\AbstractPresenter;
use Larafun\Suite\Transformers\Transformer;
use Larafun\Suite\Tests\TestCase;
use Larafun\Suite\Traits\PresentableTrait;

class PresentableTest extends TestCase
{
    /** @test */
    public function itCanSetPresenterByClassName()
    {
        $stub = new PresentableStub();
        $stub->setPresenter(Presenter::class);
        $this->assertEquals(Presenter::class, $stub->presenter());
        $this->assertInstanceOf(Presenter::class, $stub->getPresenter());
        $this->assertInstanceOf(AbstractPresenter::class, $stub->getPresenter());
    }

    /** @test */
    public function itCanSetPresenterByInstance()
    {
        $stub = new PresentableStub();
        $presenter = new Presenter($stub);
        $stub->setPresenter($presenter);
        $this->assertEquals($presenter, $stub->presenter());
        $this->assertInstanceOf(Presenter::class, $stub->getPresenter());
        $this->assertInstanceOf(AbstractPresenter::class, $stub->getPresenter());
    }

    /**
     * @test
     * @expectedException TypeError
     */
    public function itThrowsExceptionOnInvalidPresenterClassName()
    {
        $stub = new PresentableStub();
        $stub->setPresenter(Transformer::class);
        $presenter = $stub->getPresenter();
    }

    /**
     * @test
     * @expectedException TypeError
     */
    public function itThrowsExceptionOnInvalidPresenterInstance()
    {
        $stub = new PresentableStub();
        $stub->setPresenter(new Transformer($stub));
        $presenter = $stub->getPresenter();
    }
}

class PresentableStub
{
    use PresentableTrait;

    public function presenter()
    {
        return $this->presenter;
    }
}