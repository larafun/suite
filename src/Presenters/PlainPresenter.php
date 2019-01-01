<?php

namespace Larafun\Suite\Presenters;

use Larafun\Suite\Contracts\Presenter;
use Larafun\Suite\Contracts\Transformer;
use Larafun\Suite\Transformers\TransformerFactory;
use Illuminate\Support\Collection;

class PlainPresenter extends AbstractPresenter
{
    public function present()
    {
        return $this->getData();
    }
}