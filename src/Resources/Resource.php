<?php

namespace Larafun\Suite\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Larafun\Suite\Contracts\Paginatable;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\JsonResource;
use Larafun\Suite\Contracts\Resourceable;
use Illuminate\Support\Str;

class Resource extends JsonResource
{
    /**
     * The Request
     */
    protected $request;

    /**
     * Controls wether the resource should present a Collection
     * or a simple resource. If null, will try to detect based
     * on the data to be represented.
     */
    protected $shouldCollect = null;

    /**
     * The maximum depth of other Resources to be included
     */
    protected $max_depth = 3;

    /**
     * The remaining number of Resources that can be included
     */
    protected $remaining_depth;

    /**
     * Includes array merged with each item
     */
    protected $includes = [];

    /**
     * This method will be run prior to responding with the resource.
     * It is a good place to gather dependencies needed for the response.
     * Since the same Resource can be used for both Collections and singular Models
     * it's good to consider applying $this->collect($this->resource) and always treat
     * your resource as a Collection.
     */
    public function boot()
    {
        //
    }

    /**
     * Additional information to send with the resource. If you want to add additional
     * information, you should use the append method.
     */
    public function with($request)
    {
        return array_merge_recursive(
            $this->with,
            $this->append(),
            $this->pagination()
        );
    }

    /**
     * Additional information that may be added to the response
     */
    public function append(): array
    {
        return [];
    }

    /**
     * Allows us to pass any objects to be used in the resource
     */
    public function pass(array $vars)
    {
        foreach ($vars as $varname => $var) {
            $this->{$varname} = $var;
        }

        return $this;
    }

    /**
     * Includes these properties
     */
    public function include(...$includes)
    {
        foreach ($includes as $include) {
            $this->includes[] = $include;
        }

        return $this;
    }

    /**
     * Excludes these properties
     */
    public function exclude(...$excludes)
    {
        $this->includes = array_diff($this->includes, $excludes);

        return $this;
    }

    /**
     * Calls the include methods on the resource
     */
    protected function parseIncludes($resource): array
    {
        $includes = [];
        foreach ($this->includes as $include) {
            $includes = array_merge($includes, $this->{'include' . Str::studly($include)}($resource));
        }

        return $includes;
    }

    /**
     * This will gather the pagination information from your resource.
     * Extend this method if you want your pagination information to reside
     * into custom keys.
     */
    protected function pagination(): array
    {
        if ($this->resource instanceof Paginatable) {
            return $this->resource->getPaginator()->pagination();
        }
        return [];
    }

    /**
     * The parent method is overriden to allow booting the Resource before transformation.
     * It will also allow transforming a Collection using the same Resource class.
     * You should use a custom item() method on your Resource.
     */
    public function toArray($request)
    {
        $this->request = $request;
        $this->boot();
        if ($this->shouldCollect()) {
            return $this->map($this->resource);
        }
        return $this->buildItem($this->resource);
    }

    /**
     * Custom Resources should implement the 'item' method. This approach has been used so
     * that the developer may type hint the parameter of the 'item' method.
     */
    public function buildItem($resource)
    {
        if (method_exists($this, 'item')) {
            return array_merge_recursive(
                $this->item($resource),
                $this->parseIncludes($resource)
            );
        }
        return $resource;
    }

    /**
     * An easy way to define both collection responses and item responses in the same resource.
     * Just define a collectionItem method and it will be used when the expected response
     * is a Collection.
     */
    public function buildCollectionItem($resource)
    {
        if (method_exists($this, 'collectionItem')) {
            return array_merge_recursive(
                $this->collectionItem($resource),
                $this->parseIncludes($resource)
            );
        }
        return $this->buildItem($resource);
    }

    /**
     * Transforms each item of the Collection instead of transforming the Collection itself
     */
    public function map(Collection $resource)
    {
        return $resource->map(function ($value) {
            return $this->buildCollectionItem($value);
        });
    }

    /**
     * Determines wether it should transform an item or a Collection
     */
    protected function shouldCollect()
    {
        if (!is_null($this->shouldCollect)) {
            return $this->shouldCollect;
        }
        return ($this->resource instanceof Collection);
    }

    /**
     * Forces the Resource to treat the data as a Collection
     */
    public function asCollection()
    {
        if (! $this->resource instanceof Collection) {
            throw new \RuntimeException('The resource needs to be an instance of ' . Collection::class);
        }
        $this->shouldCollect = true;
        return $this;
    }

    /**
     * Forces the Resource to treat the data as an Item
     */
    public function asItem()
    {
        $this->shouldCollect = false;
        return $this;
    }

    /**
     * Build a Collection Resource
     */
    public static function collection($resource): self
    {
        return (new static($resource))->asCollection();
    }

    /**
     * Build an item Resource
     */
    public static function resource($resource): self
    {
        return (new static($resource))->asItem();
    }

    /**
     * Converts a resource to a Collection if not already.
     */
    protected function collect($resource)
    {
        if ($resource instanceof Collection) {
            return $resource;
        }
        if ($resource instanceof Model) {
            return new EloquentCollection([$resource]);
        }
        return collect([$resource]);
    }

    /**
     * Returns a Collection representation of the underlying resource
     */
    protected function collected()
    {
        return $this->collect($this->resource);
    }

    /**
     * Sets the remaining depth. If too low, it replaces the resource with a
     * Missing value to stop propagating infinitely.
     */
    public function setRemainingDepth($depth) {
        $this->remaining_depth = $depth;
        return $this;
    }

    /**
     * Returns the remaining depth or max_depth if first call.
     */
    public function getRemainingDepth()
    {
        return $this->remaining_depth ?? $this->max_depth;
    }

    /**
     * Checks if the last depth level was reached.
     */
    protected function depthIsInRange()
    {
        return ($this->getRemainingDepth() > 0);
    }

    /**
     * Returns a new resource with a reduced depth.
     */
    protected function deepen($resource, $relation = null)
    {
        if (! $this->depthIsInRange()) {
            return new MissingValue;
        }

        if (! is_null($relation)) {
            $resource = $resource->{$relation};
        }

        if ((! $resource instanceof Resource) && ($resource instanceof Resourceable)) {
            $resource = $resource->resource();
        }

        if (! $resource instanceof Resource) {
            throw new \RuntimeException('Could not deepen the resource ' . get_class($resource));
        }

        return $resource->setRemainingDepth($this->getRemainingDepth() - 1);
    }
}
