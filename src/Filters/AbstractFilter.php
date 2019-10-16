<?php

namespace Larafun\Suite\Filters;

use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Validation\ValidationException;

abstract class AbstractFilter
{
    protected $attributes = [];

    public function __construct(array $attributes = [])
    {
        if (empty($attributes)) {
            $attributes = request()->all();
        }

        $this->validate($attributes);

        $this->attributes = array_merge(
            $this->defaults(),
            $attributes
        );
    }

    abstract public function defaults(): array;

    // validation rules
    public function rules()
    {
        return [];
    }

    public function all()
    {
        return $this->attributes;
    }
    
    protected function validate(array $attributes = [], array $rules = [])
    {
        if (empty($rules)) {
            $rules = $this->rules();
        }

        $validator = Validator::make($attributes, $rules);
        if ($validator->fails()) {
            $this->failedValidation($validator);
        }
    }

    protected function failedValidation(ValidatorContract $validator)
    {
        throw new ValidationException($validator);
    }

    public function __get($key)
    {
        if (!array_key_exists($key, $this->attributes)) {
            return null;
        }
        return $this->attributes[$key];
    }

    public function __set($key, $value)
    {
        if (array_key_exists($key, $this->rules())) {
            $this->validate([$key => $value], array_intersect_key($this->rules(), [$key => '']));
        }
        $this->attributes[$key] = $value;

        return $value;
    }
}
