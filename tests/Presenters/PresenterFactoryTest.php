<?php

namespace Larafun\Suite\Tests\Presenters;

use Larafun\Suite\Contracts\Presenter as PresenterInterface;
use Larafun\Suite\Presenters\Presenter;
use Larafun\Suite\Presenters\PlainPresenter;
use Larafun\Suite\Presenters\PresenterFactory;
use Larafun\Suite\Tests\TestCase;

class PresenterFactoryTest extends TestCase
{
    /** @test */
    public function itCanUseTheDefault()
    {
        $presenter = PresenterFactory::make();
        $this->assertInstanceOf(PlainPresenter::class, $presenter);        
    }

    /** @test */
    public function itCanCreatePresenter()
    {
        $presenter = PresenterFactory::make(Presenter::class);
        $this->assertInstanceOf(PresenterInterface::class, $presenter);        
    }

    /** @test */
    public function itCanPassData()
    {
        $data = new \StdClass();

        $presenter = PresenterFactory::make(ExtendedPresenter::class, $data);
        $this->assertEquals($data, $presenter->data());
    }

    /** @test */
    public function itCanBuildWithoutData()
    {
        $presenter = PresenterFactory::make(ExtendedPresenter::class);
        $this->assertNull($presenter->data());
    }
}

class ExtendedPresenter extends Presenter
{
    public function data()
    {
        return $this->data;
    }
}