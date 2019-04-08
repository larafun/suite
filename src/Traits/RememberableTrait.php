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
        $builder = new QueryBuilder($conn, $grammar, $conn->getPostProcessor());

        $builder->remember($this->cacheTime ?? config('suite.model.cache_time'));

        $builder->cacheTags(static::getCacheTag());

        $builder->prefix(config('cache.prefix'));

        if (isset($this->cacheDriver))
            $builder->cacheDriver($this->cacheDriver);

        return $builder;
    }

    public static function getCacheTag()
    {
        return config('cache.prefix') .':'. static::class;
    }

    protected static function bootRememberableTrait()
    {
        static::flushCacheEvents()->each(function ($event) {
            return static::$event(function (Model $model) use ($event) {
                cache()->tags(static::getCacheTag())->flush();
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
