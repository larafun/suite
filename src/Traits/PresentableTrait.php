<?php

namespace Larafun\Suite\Traits;

use Larafun\Suite\Contracts\Presenter;
use Larafun\Suite\Presenters\PresenterFactory;

trait PresentableTrait
{
    protected $presenter;

    public function setPresenter($presenter)
    {
        $this->presenter = $presenter;
        return $this;
    }

    public function getPresenter(): Presenter
    {
        return PresenterFactory::make($this->presenter, $this);
    }
}
