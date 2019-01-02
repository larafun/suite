<?php

namespace Larafun\Suite\Presenters;

use Larafun\Suite\Contracts\Queryable;
use Larafun\Suite\Contracts\Presenter as PresenterInterface;
use Larafun\Suite\Contracts\Paginatable;
use Larafun\Suite\Contracts\Transformable;
use Larafun\Suite\Contracts\Transformer;
use Larafun\Suite\Transformers\TransformerFactory;
use Illuminate\Support\Collection;

abstract class AbstractPresenter implements PresenterInterface
{
    protected $data;

    protected $transformer = null;

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->present(), $options);
    }

    public function present()
    {
        return array_merge(
            $this->buildData(),
            $this->buildMeta(),
            $this->buildPagination()
        );
    }

    protected function buildData(): array
    {
        return [
            $this->getDataKey() => $this->getData()
        ];
    }

    protected function getDataKey(): string
    {
        return 'data';
    }

    protected function getData()
    {
        $transformer = TransformerFactory::make($this->getTransformer(), $this->data);

        if (! $transformer) {
            return $this->data;
        }

        $transformer->boot();

        if ($this->data instanceof Collection) {
            return $this->transformCollection($this->data, $transformer);
        }
        return $transformer->transform($this->data);
    }

    protected function transformCollection(Collection $collection, Transformer $transformer)
    {
        return $this->data->map(function ($value) use ($transformer) {
            return $transformer->transform($value);
        });
    }

    protected function buildMeta(): array
    {
        return [
            $this->getMetaKey() => $this->getMeta()
        ];
    }

    protected function getMetaKey(): string
    {
        return 'meta';
    }

    public function getMeta(): array
    {
        if (! $this->data instanceof Paginatable) {
            return [];
        }
        return [
            'pagination' => $this->data->getPaginator()->pagination()
        ];
    }

    protected function buildPagination()
    {
        return [];
    }

    public function setTransformer($transformer)
    {
        $this->transformer = $transformer;
        return $this;
    }

    public function getTransformer()
    {
        if ($this->transformer) {
            return $this->transformer;
        }
        if ($this->data instanceof Transformable) {
            return $this->data->getTransformer();
        }
        return null;
    }

}