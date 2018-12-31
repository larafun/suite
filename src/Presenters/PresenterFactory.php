<?php

namespace Larafun\Suite\Presenters;

use Larafun\Suite\Contracts\Presenter;

class PresenterFactory
{
    public static function make($instance = null, $data = null): Presenter
    {
        if (empty($instance)) {
            $instance = PlainPresenter::class;
        }
        if (is_string($instance)) {
            $presenter = app($instance, compact('data'));
        }
        return $presenter;
    }
}