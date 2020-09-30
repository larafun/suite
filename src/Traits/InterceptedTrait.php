<?php

namespace Larafun\Suite\Traits;

/**
 * Used for custom Collections that need to return basic Collections
 * when their state is altered (order or number of items) or have
 * custom dependencies that need to be instantiated in the constructor
 */
trait InterceptedTrait
{
    /**
     * Get the median of a given key.
     *
     * @param  string|array|null $key
     * @return mixed
     */
    public function median($key = null)
    {
        return $this->toBase()->median($key);
    }

    /**
     * Collapse the collection of items into a single array.
     *
     * @return static
     */
    public function collapse()
    {
        return $this->toBase()->collapse();
    }

    /**
     * Cross join with the given lists, returning all possible permutations.
     *
     * @param  mixed  ...$lists
     * @return static
     */
    public function crossJoin(...$lists)
    {
        return $this->toBase()->crossJoin(...$lists);
    }

    /**
     * Get the items in the collection that are not present in the given items.
     *
     * @param  mixed  $items
     * @return static
     */
    public function diff($items)
    {
        return $this->toBase()->diff($items);
    }

    /**
     * Get the items in the collection that are not present in the given items.
     *
     * @param  mixed  $items
     * @param  callable  $callback
     * @return static
     */
    public function diffUsing($items, callable $callback)
    {
        return $this->toBase()->diffUsing($items, $callback);
    }
    /**
     * Get the items in the collection whose keys and values are not present in the given items.
     *
     * @param  mixed  $items
     * @return static
     */
    public function diffAssoc($items)
    {
        return $this->toBase()->diffAssoc($items);
    }

    /**
     * Get the items in the collection whose keys and values are not present in the given items.
     *
     * @param  mixed  $items
     * @param  callable  $callback
     * @return static
     */
    public function diffAssocUsing($items, callable $callback)
    {
        return $this->toBase()->diffAssocUsing($items, $callback);
    }

    /**
     * Get the items in the collection whose keys are not present in the given items.
     *
     * @param  mixed  $items
     * @return static
     */
    public function diffKeys($items)
    {
        return $this->toBase()->diffKeys($items);
    }

    /**
     * Get the items in the collection whose keys are not present in the given items.
     *
     * @param  mixed   $items
     * @param  callable  $callback
     * @return static
     */
    public function diffKeysUsing($items, callable $callback)
    {
        return $this->toBase()->diffKeysUsing($items, $callback);
    }

    /**
     * Get all items except for those with the specified keys.
     *
     * @param  \Illuminate\Support\Collection|mixed  $keys
     * @return static
     */
    public function except($keys)
    {
        return $this->toBase()->except($keys);
    }

    /**
     * Run a filter over each of the items.
     *
     * @param  callable|null  $callback
     * @return static
     */
    public function filter(callable $callback = null)
    {
        return $this->toBase()->filter($callback);
    }

    /**
     * Get a flattened array of the items in the collection.
     *
     * @param  int  $depth
     * @return static
     */
    public function flatten($depth = INF)
    {
        return $this->toBase()->flatten($depth);
    }

    /**
     * Flip the items in the collection.
     *
     * @return static
     */
    public function flip()
    {
        return $this->toBase()->flip();
    }

    /**
     * Remove an item from the collection by key.
     *
     * @param  string|array  $keys
     * @return $this
     */
    public function forget($keys)
    {
        return $this->toBase()->forget($keys);
    }

    /**
     * Group an associative array by a field or using a callback.
     *
     * @param  array|callable|string  $groupBy
     * @param  bool  $preserveKeys
     * @return static
     */
    public function groupBy($groupBy, $preserveKeys = false)
    {
        return $this->toBase()->groupBy($groupBy, $preserveKeys);
    }

    /**
     * Key an associative array by a field or using a callback.
     *
     * @param  callable|string  $keyBy
     * @return static
     */
    public function keyBy($keyBy)
    {
        return $this->toBase()->keyBy($keyBy);
    }

    /**
     * Intersect the collection with the given items.
     *
     * @param  mixed  $items
     * @return static
     */
    public function intersect($items)
    {
        return $this->toBase()->intersect($items);
    }

    /**
     * Intersect the collection with the given items by key.
     *
     * @param  mixed  $items
     * @return static
     */
    public function intersectByKeys($items)
    {
        return $this->toBase()->intersectByKeys($items);
    }

    
    /**
     * Get the keys of the collection items.
     *
     * @return static
     */
    public function keys()
    {
        return $this->toBase()->keys();
    }

    /**
     * Get the values of a given key.
     *
     * @param  string|array  $value
     * @param  string|null  $key
     * @return static
     */
    public function pluck($value, $key = null)
    {
        return $this->toBase()->pluck($value);
    }

    /**
     * Run a map over each of the items.
     *
     * @param  callable  $callback
     * @return static
     */
    public function map(callable $callback)
    {
        return $this->toBase()->map($callback);
    }

    /**
     * Run a dictionary map over the items.
     *
     * The callback should return an associative array with a single key/value pair.
     *
     * @param  callable  $callback
     * @return static
     */
    public function mapToDictionary(callable $callback)
    {
        return $this->toBase()->mapToDictionary($callback);
    }

    /**
     * Run an associative map over each of the items.
     *
     * The callback should return an associative array with a single key/value pair.
     *
     * @param  callable  $callback
     * @return static
     */
    public function mapWithKeys(callable $callback)
    {
        return $this->toBase()->mapWithKeys($callback);
    }

    /**
     * Merge the collection with the given items.
     *
     * @param  mixed  $items
     * @return static
     */
    public function merge($items)
    {
        return $this->toBase()->merge($items);
    }

    /**
     * Create a collection by using this collection for keys and another for its values.
     *
     * @param  mixed  $values
     * @return static
     */
    public function combine($values)
    {
        return $this->toBase()->combine($values);
    }

    /**
     * Union the collection with the given items.
     *
     * @param  mixed  $items
     * @return static
     */
    public function union($items)
    {
        return $this->toBase()->union($items);
    }

    /**
     * Create a new collection consisting of every n-th element.
     *
     * @param  int  $step
     * @param  int  $offset
     * @return static
     */
    public function nth($step, $offset = 0)
    {
        return $this->toBase()->nth($step, $offset);
    }

    /**
     * Get the items with the specified keys.
     *
     * @param  mixed  $keys
     * @return static
     */
    public function only($keys)
    {
        return $this->toBase()->only($keys);
    }

    /**
     * Partition the collection into two arrays using the given callback or key.
     *
     * @param  callable|string  $key
     * @param  mixed  $operator
     * @param  mixed  $value
     * @return static
     */
    public function partition($key, $operator = null, $value = null)
    {
        return $this->toBase()->partition($key, $operator, $value);
    }

    /**
     * Get and remove the last item from the collection.
     *
     * @return mixed
     */
    public function pop()
    {
        return $this->toBase()->pop();
    }

    /**
     * Push an item onto the beginning of the collection.
     *
     * @param  mixed  $value
     * @param  mixed  $key
     * @return $this
     */
    public function prepend($value, $key = null)
    {
        return $this->toBase()->prepend($value, $key);
    }

    /**
     * Push an item onto the end of the collection.
     *
     * @param  mixed  $value
     * @return $this
     */
    public function push(...$values)
    {
        return $this->toBase()->push($values);
    }

    /**
     * Push all of the given items onto the collection.
     *
     * @param  iterable  $source
     * @return static
     */
    public function concat($source)
    {
        return $this->toBase()->concat($source);
    }

    /**
     * Get and remove an item from the collection.
     *
     * @param  mixed  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function pull($key, $default = null)
    {
        return $this->toBase()->pull($key, $default);
    }

    /**
     * Put an item in the collection by key.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return $this
     */
    public function put($key, $value)
    {
        return $this->toBase()->out($key, $value);
    }

    /**
     * Get one or a specified number of items randomly from the collection.
     *
     * @param  int|null  $number
     * @return static|mixed
     *
     * @throws \InvalidArgumentException
     */
    public function random($number = null)
    {
        return $this->toBase()->random($number);
    }

    /**
     * Reverse items order.
     *
     * @return static
     */
    public function reverse()
    {
        return $this->toBase()->reverse();
    }

    /**
     * Get and remove the first item from the collection.
     *
     * @return mixed
     */
    public function shift()
    {
        return $this->toBase()->shift();
    }

    /**
     * Shuffle the items in the collection.
     *
     * @param  int  $seed
     * @return static
     */
    public function shuffle($seed = null)
    {
        return $this->toBase()->shuffle($seed);
    }

    /**
     * Slice the underlying collection array.
     *
     * @param  int  $offset
     * @param  int  $length
     * @return static
     */
    public function slice($offset, $length = null)
    {
        return $this->toBase()->slice($offset, $length);
    }

    /**
     * Split a collection into a certain number of groups.
     *
     * @param  int  $numberOfGroups
     * @return static
     */
    public function split($numberOfGroups)
    {
        return $this->toBase()->split($numberOfGroups);
    }

    /**
     * Chunk the underlying collection array.
     *
     * @param  int  $size
     * @return static
     */
    public function chunk($size)
    {
        return $this->toBase()->chunk($size);
    }

    /**
     * Sort through each item with a callback.
     *
     * @param  callable|null  $callback
     * @return static
     */
    public function sort($callback = null)
    {
        return $this->toBase()->sort($callback);
    }

    /**
     * Sort the collection using the given callback.
     *
     * @param  callable|string  $callback
     * @param  int  $options
     * @param  bool  $descending
     * @return static
     */
    public function sortBy($callback, $options = SORT_REGULAR, $descending = false)
    {
        return $this->toBase()->sortBy($callback, $options, $descending);
    }

    /**
     * Sort the collection keys.
     *
     * @param  int  $options
     * @param  bool  $descending
     * @return static
     */
    public function sortKeys($options = SORT_REGULAR, $descending = false)
    {
        return $this->toBase()->sortKeys($options, $descending);
    }

    /**
     * Splice a portion of the underlying collection array.
     *
     * @param  int  $offset
     * @param  int|null  $length
     * @param  mixed  $replacement
     * @return static
     */
    public function splice($offset, $length = null, $replacement = [])
    {
        return $this->toBase()->splice($offset, $length, $replacement);
    }

    /**
     * Pass the collection to the given callback and then return it.
     *
     * @param  callable  $callback
     * @return $this
     */
    public function tap(callable $callback)
    {
        return $this->toBase()->tap($callback);
    }

    /**
     * Reset the keys on the underlying array.
     *
     * @return static
     */
    public function values()
    {
        return $this->toBase()->values();
    }

    /**
     * Zip the collection together with one or more arrays.
     *
     * e.g. new Collection([1, 2, 3])->zip([4, 5, 6]);
     *      => [[1, 4], [2, 5], [3, 6]]
     *
     * @param  mixed ...$items
     * @return static
     */
    public function zip($items)
    {
        return $this->toBase()->zip($items);
    }

    /**
     * Pad collection to the specified length with a value.
     *
     * @param  int  $size
     * @param  mixed  $value
     * @return static
     */
    public function pad($size, $value)
    {
        return $this->toBase()->pad($size, $value);
    }
}
