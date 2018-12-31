<?php

namespace Larafun\Suite\Transformer;

use Larafun\Suite\Contracts\Transformer\Transformer as TransformerInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

class Transformer
{
    protected $data;

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function boot()
    {
        dd($this->data);
        $this->data = ['hello'];
    }

    public function transform($object)
    {
        if ($object instanceof JsonSerializable) {
            return $object->jsonSerialize();
        } elseif ($object instanceof Jsonable) {
            return json_decode($object->toJson(), true);
        } elseif ($object instanceof Arrayable) {
            return $object->toArray();
        }
        return $object;
    }
}
