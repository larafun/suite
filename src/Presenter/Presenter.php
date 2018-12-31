<?php

namespace Larafun\Suite\Presenter;

use Larafun\Suite\Transformer\Transformer;
use Larafun\Suite\Transformer\SimpleTransformer;

class Presenter
{
    protected $data;

    public function __construct($data = null)
    {
        $this->data = $data;
        $this->setTransformer(app()->makeWith(SimpleTransformer::class, ['data' => $data]));
    }

    public function toJson($options): string
    {
        return json_encode($this->present(), $options);
    }

    public function toArray(): array
    {
        $output = [
            $this->getDataKey()  => $this->getData(),
            $this->getMetaKey()  => $this->getMeta()
        ];

        return $output;
    }

    public function present()
    {
        return $this->toArray();
    }

    public function getDataKey(): string
    {
        return 'data';
    }

    public function getData()
    {
        return $this->transform($this->data);
    }

    protected function transform($data)
    {
        $this->transformer()->boot();
        return $this->transformer()->transform($data);
    }

    public function transformer()
    {
        return $this->transformer;
    }

    public function setTransformer($transformer)
    {
        $this->transformer = $transformer;
        return $this;
    }

    public function getMeta()
    {
        return array_merge(
            $this->getGlobalMeta(),
            ['nothing' => 'here']
        );
    }

    public function getMetaKey(): string
    {
        return 'meta';
    }

    protected function getGlobalMeta()
    {
        return [
            'url'   => request()->url()
        ];
    }
}
