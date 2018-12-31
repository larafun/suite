<?php

namespace Larafun\Suite\Contracts;

interface Transformable
{
    public function setTransformer($transformer);

    public function getTransformer();
}