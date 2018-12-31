<?php

namespace Larafun\Suite\Presenters;

use Larafun\Suite\Contracts\Presenter;
use Larafun\Suite\Contracts\Transformer;
use Larafun\Suite\Transformers\TransformerFactory;
use Illuminate\Support\Collection;

class PlainPresenter implements Presenter
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
        return $this->getData();
    }


    public function getData()
    {
        $transformer = TransformerFactory::make($this->getTransformer(), $this->data);

        if (! $transformer) {
            return $this->data;
        }

        $transformer->boot();

        if ($this->data instanceof Collection) {
            return $this->transformCollection($this->data, $transformer);
        }
        return $this->transformItem($data);
    }

    public function transformCollection(Collection $collection, Transformer $transformer)
    {
        return $this->data->map(function ($value) use ($transformer) {
            return $transformer->transform($value);
        });
    }

    public function transformWith($transformer)
    {
        $this->transformer = $transformer;
        return $this;
    }

    public function getTransformer()
    {
        return $this->data->getTransformer();
        return app(Transformer::class, ['data' => $this->data]);
    }

    public function getMeta()
    {
        $query = clone $this->data->getQuery();
        return [
            'skip'  => $query->offset,
            'take'  => $query->limit,
            'total' => $query->skip(0)->take(PHP_INT_MAX)->count(),
        ];
    }
}