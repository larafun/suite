<?php

namespace Larafun\Suite\Contracts;

use Illuminate\Contracts\Support\Jsonable;

interface Presenter extends Jsonable
{
    public function present();
}