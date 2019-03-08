<?php

namespace Larafun\Suite\Traits;

use Larafun\Suite\Resources\PlainResource;

trait ResourceableTrait
{
    public function toResponse($request)
    {
        return app($this->getResource(), ['resource' => $this])->response();
    }

    public function getResource()
    {
        return PlainResource::class;
    }
}
