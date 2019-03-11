<?php

namespace Larafun\Suite\Resources;

/**
 * A resource that doesn't transform it's data.
 * It places the pagination data inside a 'meta' => 'pagination' field
 */
class MetaPaginationResource extends Resource
{
    protected function pagination()
    {
        return [
            'meta' => [
                'pagination' => parent::pagination()
            ]
        ];
    }
}
