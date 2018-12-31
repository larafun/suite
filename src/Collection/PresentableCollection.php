<?php

namespace Larafun\Suite\Collection;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Larafun\Suite\Contracts\Collection\QueryAware;
use Illuminate\Database\Query\Builder;
use Larafun\Suite\Contracts\Presenter\Presenter as PresenterInterface;
use Larafun\Suite\Contracts\Transformer\Transformer as TransformerInterface;
use Larafun\Suite\Presenter\Presenter;
use Larafun\Suite\Presenter\PaginationPresenter;
use Larafun\Suite\Transformer\Transformer;

class PresentableCollection extends EloquentCollection implements QueryAware
{
    protected $query;
    protected $presenter;
    protected $transformer;

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

    public function setQuery(Builder $query)
    {
        $this->query = $query;
        return $this;
    }

    public function query()
    {
        return $this->query;
    }
}
