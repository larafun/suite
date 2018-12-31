<?php

namespace Larafun\Suite\Presenters;

use Larafun\Suite\Contracts\Presenter;

class PresenterFactory
{
    public static function make($presenter = null, $data = null): Presenter
    {
        if (empty($presenter)) {
            $presenter = PlainPresenter::class;
        }
        if (is_string($presenter)) {
            $presenter = app($presenter, compact('data'));
        }
        return $presenter;
    }
}