<?php

namespace Larafun\Suite\Traits;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Model;

trait ValidatableTrait
{

    protected static function bootValidatableTrait()
    {
        static::creating(function (Model $model) {
            $model->applyValidationRules('creating', $model);
        });
        
        static::updating(function (Model $model) {
            $model->applyValidationRules('updating', $model);
        });

        static::saving(function (Model $model) {
            $model->applyValidationRules('saving', $model);
        });
    }

    protected function applyValidationRules($method, Model $model)
    {
        $method = $method . 'Rules';
        $validator = Validator::make($model->attributesToArray(), $model->$method());
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function savingRules()
    {
        return [
            // your rules on saving
        ];
    }

    public function creatingRules()
    {
        return [
            // your rules on creating
        ];
    }

    public function updatingRules()
    {
        return [
            // your rules on updating
        ];
    }
}
