<?php

namespace Larafun\Suite;

use DateTime;

class QueryBuilder extends \Illuminate\Database\Query\Builder
{
    /**
     * The key that should be used when caching the query.
     *
     * @var string
     */
    protected $cacheKey;

    /**
     * The number of seconds to cache the query.
     *
     * @var int
     */
    protected $cacheSeconds;

    /**
     * The tags for the query cache.
     *
     * @var array
     */
    protected $cacheTags;

    /**
     * The model name
     *
     * @var string
     */
    protected $modelName;

    /**
     * The cache driver to be used.
     *
     * @var string
     */
    protected $cacheDriver;

    /**
     * A cache prefix.
     *
     * @var string
     */
    protected $cachePrefix = 'rememberable';

    /**
     * Execute the query as a "select" statement.
     *
     * @param  array  $columns
     * @return array|static[]
     */
    public function get($columns = ['*'])
    {
        if (! is_null($this->cacheSeconds))
            return $this->getCached($columns);

        return parent::get($columns);
    }

    /**
     * Execute the query as a cached "select" statement.
     *
     * @param  array  $columns
     * @return array
     */
    public function getCached($columns = ['*'])
    {
        if (is_null($this->columns))
            $this->columns = $columns;

        // If the query is requested to be cached, we will cache it using a unique key
        // for this database connection and query statement, including the bindings
        // that are used on this query, providing great convenience when caching.
        list($key, $seconds) = $this->getCacheInfo();

        $cache = $this->getCache();

        $callback = $this->getCacheCallback($columns);

        // If we've been given a DateTime instance or a "seconds" value that is
        // greater than zero then we'll pass it on to the remember method.
        // Otherwise we'll cache it indefinitely.
        if ($seconds instanceof DateTime || $seconds > 0)
            return $cache->remember($key, $seconds, $callback);

        return $cache->rememberForever($key, $callback);
    }

    /**
     * Indicate that the query results should be cached.
     *
     * @param  \DateTime|int  $seconds
     * @param  string  $key
     * @return $this
     */
    public function remember(int $seconds = null, string $key = null)
    {
        $seconds = $seconds ?? config('suite.model.cache_time');

        list($this->cacheSeconds, $this->cacheKey) = [$seconds, $key];

        return $this;
    }

    /**
     * Indicate that the query results should be cached forever.
     *
     * @param  string  $key
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function rememberForever(string $key = null)
    {
        return $this->remember(-1, $key);
    }

    /**
     * Indicate that the query should not be cached.
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function dontRemember()
    {
        $this->cacheSeconds = $this->cacheKey = $this->cacheTags = null;

        return $this;
    }

    /**
     * Indicate that the query should not be cached. Alias for dontRemember().
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function doNotRemember()
    {
        return $this->dontRemember();
    }

    /**
     * Indicate that the results, if cached, should use the given cache tags.
     *
     * @param  mixed  $cacheTags
     * @return $this
     */
    public function cacheTags($cacheTags)
    {
        $this->cacheTags = $cacheTags;

        return $this;
    }

    /**
     * Indicate that the results, if cached, should use the given cache driver.
     *
     * @param  string  $cacheDriver
     * @return $this
     */
    public function cacheDriver(string $cacheDriver)
    {
        $this->cacheDriver = $cacheDriver;

        return $this;
    }

    /**
     * Get the cache object with tags assigned, if applicable.
     *
     * @return \Illuminate\Cache\CacheManager
     */
    protected function getCache()
    {
        $cache = $this->getCacheDriver();

        return $this->cacheTags ? $cache->tags($this->cacheTags) : $cache;
    }

    /**
     * Get the cache driver.
     *
     * @return \Illuminate\Cache\CacheManager
     */
    protected function getCacheDriver()
    {
        return app('cache')->driver($this->cacheDriver);
    }

    /**
     * Get the cache key and cache seconds as an array.
     *
     * @return array
     */
    protected function getCacheInfo()
    {
        return [$this->getCacheKey(), $this->cacheSeconds];
    }

    /**
     * Get a unique cache key for the complete query.
     *
     * @return string
     */
    public function getCacheKey()
    {
        return $this->cachePrefix.':'.$this->modelName.':'.($this->cacheKey ?: $this->generateCacheKey());
    }

    /**
     * Generate the unique cache key for the query.
     *
     * @return string
     */
    public function generateCacheKey()
    {
        $name = $this->connection->getName();

        return hash('sha256', $name.$this->toSql().serialize($this->getBindings()));
    }

    /**
     * Flush the cache for the current model
     *
     * @return boolean
     */
    public function flushCache()
    {
        $cache = $this->getCacheDriver();

        if ( ! method_exists($cache, 'tags'))
            return false;

        $cache->tags($this->cacheTags)->flush();

        return true;
    }

    /**
     * Get the Closure callback used when caching queries.
     *
     * @param  array  $columns
     * @return \Closure
     */
    protected function getCacheCallback($columns)
    {
        return function () use ($columns) {
            $this->cacheSeconds = null;

            return $this->get($columns);
        };
    }

    /**
     * Set the cache prefix.
     *
     * @param string $prefix
     *
     * @return $this
     */
    public function prefix(string $prefix)
    {
        $this->cachePrefix = $prefix;

        return $this;
    }

    /**
     * Set the model name
     *
     * @param string $name
     *
     * @return $this
     */
    public function modelName(string $name)
    {
        $this->modelName = $name;

        return $this;
    }
}