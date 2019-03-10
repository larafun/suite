<?php

namespace Larafun\Suite\Traits;

use Larafun\Suite\Resources\PlainResource;

trait ResourceableTrait
{
    private $resource;

    public function toResponse($request)
    {
        return $this->resource()->response();
    }

    /**
     * Returns a resource decorated object as a singleton, so that it's state
     * may be changed during runtime
     */
    public function resource()
    {
        if (!$this->resource) {
            $this->resource = app($this->getResource(), [
                'resource'  => $this
            ]);
        }
        return $this->resource;
    }

    /**
     * Get the default resource that will decorate this object
     */
    public function getResource()
    {
        return PlainResource::class;
    }
}
