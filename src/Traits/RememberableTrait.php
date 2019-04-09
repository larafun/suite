<?php

namespace Larafun\Suite\Traits;

use Illuminate\Database\Eloquent\Model;
use Larafun\Suite\QueryBuilder;

trait RememberableTrait
{
    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();
        $grammar = $conn->getQueryGrammar();
        $processor = $conn->getPostProcessor();
        $builder = new QueryBuilder($conn, $grammar, $processor);

        //this causes the models with the trait to be cached by default
        $builder->remember($this->cacheTime ?? config('suite.model.cache_time'));

        $builder->cacheTags(static::getCacheTag());

        $builder->modelName(static::class);

        $builder->prefix(config('cache.prefix'));

        if (isset($this->cacheDriver))
            $builder->cacheDriver($this->cacheDriver);

        return $builder;
    }

    public static function getCacheTag()
    {
        //the prefix is added in case the same model exists in other apps
        return config('cache.prefix') .':'. static::class;
    }

    protected static function bootRememberableTrait()
    {
        //we clear the cache on saved, deleted or restored
        static::flushCacheEvents()->each(function ($event) {
            return static::$event(function (Model $model) use ($event) {
                $model->flushCache();
            });
        });
    }

    protected static function flushCacheEvents()
    {
        $events = collect([
            'saved',
            'deleted',
        ]);

        if (collect(class_uses_recursive(static::class))->contains(SoftDeletes::class))
            $events->push('restored');

        return $events;
    }

}
