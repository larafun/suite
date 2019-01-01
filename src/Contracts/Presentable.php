<?php

namespace Larafun\Suite\Contracts;

use Larafun\Suite\Contracts\Presenter;

interface Presentable
{
    public function setPresenter($presenter);

    public function getPresenter(): Presenter;
}