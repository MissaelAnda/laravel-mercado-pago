<?php

namespace MissaelAnda\MercadoPago\Resources;

/**
 * @template T extends Resource|object
 *
 * @method static setPaging(Page $page)
 */
class PaginatedResult extends Resource
{
    public Page $paging;

    /**
     * @var array<T>
     */
    public array $results;

    /**
     * @param  class-string<Resource|object>  $resource
     */
    public function __construct(
        protected string $resource,
        array $data = [],
        protected ?string $mapResults = null,
    ) {
        parent::__construct($data);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset == $this->mapResults) {
            $offset = 'results';
        }

        parent::offsetSet($offset, $value);
    }

    public function setResults(array $results): static
    {
        $this->results = array_map(fn ($item) => is_array($item) ? new ($this->resource)($item) : $item, $results);

        return $this;
    }

    /**
     * @return ?T
     */
    public function get(int $offset)
    {
        if ($offset < 0) {
            $offset = count($this->results) + $offset;
        }

        return $this->results[$offset] ?? null;
    }

    public function offsetExists(mixed $offset): bool
    {
        if (is_int($offset)) {
            return isset($this->results[$offset]);
        }

        return parent::offsetExists($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        if (is_int($offset)) {
            return $this->get($offset);
        }

        return parent::offsetGet($offset);
    }
}
