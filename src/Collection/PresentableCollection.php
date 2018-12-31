<?php

namespace Larafun\Suite\Collection;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Query\Builder;
use Larafun\Suite\Contracts\Queryable;
use Larafun\Suite\Contracts\Presentable;
use Larafun\Suite\Contracts\Transformable;
use Larafun\Suite\Presenters\Presenter;
use Larafun\Suite\Presenter\PaginationPresenter;
use Larafun\Suite\Transformer\Transformer;
use Larafun\Suite\Traits\QueryableTrait;
use Larafun\Suite\Traits\PresentableTrait;
use Larafun\Suite\Traits\TransformableTrait;

class PresentableCollection extends EloquentCollection implements Queryable, Presentable, Transformable
{
    use QueryableTrait, PresentableTrait, TransformableTrait;

    public function __construct($items = [])
    {
        parent::__construct($items);

        $this->presentWith(new Presenter($this));
    }

    public function toJson($options = 0)
    {
        return $this->presenter()
            ->toJson($options)
        ;
    }

    public function present()
    {
        dd('present');
        return $this->presenter()->present();
    }

    public function presentWith($presenter)
    {
        $this->presenter = $presenter;
        return $this;
    }

    public function presenter()
    {
        return $this->presenter;
    }

}
