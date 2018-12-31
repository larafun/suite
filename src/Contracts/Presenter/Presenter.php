<?php

namespace Larafun\Suite\Contracts\Presenter;

use Larafun\Suite\Contracts\Transformer\Transformer;

interface Presenter
{
    public function present($items);

    public function transformWith(Transformer $transformer);

    public function toJson($options = 0);
}
