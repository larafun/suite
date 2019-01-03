<?php

namespace Larafun\Suite\Presenters;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Larafun\Suite\Contracts\Presenter;

class PresenterFactory
{
    public static function make($presenter = null, $data = null): Presenter
    {
        if (empty($presenter)) {
            $presenter = self::getDefaultPresenter($data);
        }
        if (is_string($presenter)) {
            $presenter = app($presenter, compact('data'));
        }
        return $presenter;
    }

    protected static function getDefaultPresenter($data = null)
    {
        $default = Presenter::class;
        if ($data instanceof Model) {
            return config('suite.model.presenter', $default);
        } elseif ($data instanceof Collection) {
            return config('suite.collection.presenter', $default);
        }
        return $default;
    }

}