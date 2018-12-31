<?php

namespace Larafun\Suite\Presenter;

use Larafun\Suite\Contracts\Transformer\Transformer;
use Larafun\Suite\Contracts\Presenter\Presenter as PresenterInterface;

abstract class PresenterAbstract implements PresenterInterface
{
    protected $transformer;

    public function present($items)
    {
        $this->items = $items;
        return $this;
    }

    abstract public function toJson($options = 0);

    public function transformWith(Transformer $transformer)
    {
        $this->transformer = $transformer;
        return $this;
    }

    public function transformer()
    {
        return $this->transformer;
    }
}
