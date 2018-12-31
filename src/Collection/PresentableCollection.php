<?php

namespace Larafun\Suite\Collection;

use Illuminate\Database\Eloquent\Collection;
use Larafun\Suite\Contracts\Queryable;
use Larafun\Suite\Contracts\Presentable;
use Larafun\Suite\Contracts\Transformable;
use Larafun\Suite\Presenters\Presenter;
use Larafun\Suite\Traits\QueryableTrait;
use Larafun\Suite\Traits\PresentableTrait;
use Larafun\Suite\Traits\TransformableTrait;

class PresentableCollection extends Collection implements Queryable, Presentable, Transformable
{
    use QueryableTrait, PresentableTrait, TransformableTrait;

    public function __construct($items = [])
    {
        parent::__construct($items);

        $this->setPresenter(new Presenter($this));
    }

    public function toJson($options = 0)
    {
        return $this->getPresenter()
            ->toJson($options)
        ;
    }
}
