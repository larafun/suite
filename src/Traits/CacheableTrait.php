<?php

namespace Larafun\Suite\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Larafun\Suite\QueryBuilder;

trait CacheableTrait
{
    protected $cacheDriver;
    protected $cacheKey;
    protected $cacheTags;

    protected function cacheSetup()
    {
        //this causes the models with the trait to be cached by default
        $this
            ->setCacheDriver(config('cache.default'))
            ->remember($this->cacheTime ?? config('suite.model.cache_time'))
            ->cacheTags(static::getCacheTag());
    }

    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();
        $grammar = $conn->getQueryGrammar();
        $processor = $conn->getPostProcessor();
        $builder = new QueryBuilder($conn, $grammar, $processor);

        $this->cacheSetup();
        $builder->setModel($this);

        return $builder;
    }

    public static function getCacheTag()
    {
        return static::class;
    }

    protected static function bootCacheableTrait()
    {
        //we clear the cache on saved, deleted or restored
        static::flushCacheEvents()->each(function ($event) {
            return static::$event(function (Model $model) use ($event) {
                $single = $model->getSingleModelCache();
                $event === 'deleted' ? $model->flushCache($single) : $model->refreshCache($single);
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

    public function remember(int $seconds = null, string $key = null)
    {
        $this->cacheTime = $seconds ?? config('suite.model.cache_time');
        $this->cacheKey = $key;

        return $this;
    }

    public function rememberForever(string $key = null)
    {
        return $this->remember(-1, $key);
    }

    public function dontRemember()
    {
        $this->cacheTime = $this->cacheKey = $this->cacheTags = null;
        return $this;
    }

    public function doNotRemember()
    {
        return $this->dontRemember();
    }

    public function cacheTags($cacheTags)
    {
        $this->cacheTags = $cacheTags;
        return $this;
    }

    public function setCacheDriver(string $cacheDriver)
    {
        $this->cacheDriver = $cacheDriver;
        return $this;
    }

    public function getCache()
    {
        $cache = $this->getCacheDriver();
        return $this->cacheTags ? $cache->tags($this->cacheTags) : $cache;
    }

    protected function getCacheDriver()
    {
        return app('cache')->driver($this->cacheDriver);
    }

    public function getCacheTime()
    {
        return $this->cacheTime ?? null;
    }
    public function setCacheTime(int $value = null)
    {
        $this->cacheTime = $value;
        return $this;
    }

    public function getSingleModelCache()
    {
        return $this->singleModelCache ?? false;
    }

    public function getCacheKey()
    {
        return self::getCacheTag().':'.($this->cacheKey ?: $this->generateCacheKey());
    }

    public function getSingleCacheKey($id)
    {
        return self::getCacheTag().':'.$id;
    }

    public function generateCacheKey()
    {
        $query = $this->getConnectionName().$this->toSql().serialize($this->getBindings());

        return hash('md5', $query);
    }

    public function flushCache(bool $single = false)
    {
        $cache = $this->getCacheDriver();

        if ($single) {
            $cache->forget($this->getSingleCacheKey($this->getKey()));
            return true;
        }

        if (! method_exists($cache, 'tags'))
            return false;

        $cache->tags($this->cacheTags)->flush();

        return true;
    }

    public function refreshCache(bool $single = false)
    {
        $this->flushCache($single);

        if ($single)
            $this->find($this->getKey());

        return true;
    }

}
