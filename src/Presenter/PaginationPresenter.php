<?php

namespace Larafun\Suite\Presenter;

use Larafun\Suite\Contracts\Collection\QueryAware;

class PaginationPresenter extends PresenterAbstract
{
    public function toJson($options = 0)
    {
        return json_encode([
            'data'          => $this->getData(),
            'pagination'    => $this->getPagination()
        ], $options);
    }

    public function getData()
    {
        $transformer = $this->transformer();

        return $this->items->map(function ($value) use ($transformer) {
            return $transformer->transform($value);
        });
    }

    public function getPagination()
    {
        if (! $this->items instanceof QueryAware) {
            throw new \InvalidArgumentException('PaginationPresenter can only present QueryAware Collections');
        }

        $query = clone $this->items->query();
        return [
            'skip'  => $query->offset ?? 0,
            'take'  => $query->limit ?? PHP_INT_MAX,
            'total' => $query->skip(0)->take(PHP_INT_MAX)->count(),
        ];
    }
}
