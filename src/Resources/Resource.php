<?php

namespace Larafun\Suite\Resources;

use Illuminate\Http\Resources\Json\Resource as BaseResource;
use Illuminate\Support\Collection;
use Illuminate\Http\Resources\MissingValue;
use Larafun\Suite\Contracts\Paginatable;

class Resource extends BaseResource
{
    protected $max_depth = 3;
    protected $depth = 1;
    protected $request;
    protected $shouldCollect = null;

    public function boot()
    {
        //
    }

    public function with($request)
    {
        return array_merge(
            $this->with,
            $this->pagination()
        );
    }

    protected function pagination()
    {
        if ($this->resource instanceof Paginatable) {
            return $this->resource->getPaginator()->pagination();
        }
        return [];
    }

    public function toArray($request)
    {
        $this->request = $request;
        $this->boot();
        if ($this->shouldCollect()) {
            return $this->map($this->resource);
        }
        return $this->item($this->resource);
    }

    public function item($resource)
    {
        return $resource;
    }

    public function map(Collection $resource)
    {
        return $resource->map(function ($value) {
            return $this->item($value);
        });
    }

    protected function shouldCollect()
    {
        if (!is_null($this->shouldCollect)) {
            return $this->shouldCollect;
        }
        return ($this->resource instanceof Collection);
    }

    public function asCollection()
    {
        if (! $this->resource instanceof Collection) {
            throw new \RuntimeException('The resource needs to be an instance of ' . Collection::class);
        }
        $this->shouldCollect = true;
        return $this;
    }

    public function asItem()
    {
        $this->shouldCollect = false;
        return $this;
    }

    public static function collection($resource)
    {       
        return (new static($resource))->asCollection();
    }

    public static function resource($resource)
    {
        return new static($resource);
    }

    public function setDepth($depth) {
        $this->depth = $depth;
        if ($this->depth > $this->max_depth) {
            $this->resource = new MissingValue;
        }
        return $this;
    }

    protected function deepen(Resource $resource)
    {
        return $resource->setDepth($this->depth + 1);
    }
}
