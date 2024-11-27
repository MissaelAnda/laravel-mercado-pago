<?php

namespace MissaelAnda\MercadoPago\Resources;

use Carbon\CarbonImmutable;
use DateTimeInterface;
use MissaelAnda\MercadoPago\Utils;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Traits\Conditionable;

abstract class Resource implements Arrayable, \ArrayAccess, \JsonSerializable
{
    use Conditionable;

    /**
     * Cache for property mapper/caster
     *
     * @var array<string,array<string,array|null>>
     */
    protected static array $propertyMapCache = [];

    /**
     * @var array<string,array<string>>
     */
    protected static array $propertiesCache = [];

    /**
     * Keeps all the raw data given to the resource
     */
    protected array $data = [];

    public function __construct(array $data = [])
    {
        $this->set($data);
    }

    public static function make(array $data = []): static
    {
        return new static($data); // @phpstan-ignore-line
    }

    public function set(array $data): static
    {
        $this->data = array_merge($this->data, $data);

        foreach ($data as $key => $value) {
            $this[$key] = $value;
        }

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    protected static function extractProperties(): array
    {
        if (!array_key_exists(static::class, static::$propertiesCache)) {
            static::$propertiesCache[static::class] = [];

            $reflection = new \ReflectionClass(static::class);
            foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
                static::$propertiesCache[static::class][] = $property->getName();
            }
        }

        return static::$propertiesCache[static::class];
    }

    public function propertiesToArray()
    {
        $data = [];
        foreach (static::extractProperties() as $property) {
            if (isset($this->{$property})) {
                $data[$property] = $this->{$property};
            }
        }

        return $data;
    }

    public function toArray()
    {
        return Utils::toRequestArray(array_map(fn ($item) => $item instanceof Arrayable ?
            $item->toArray() : $item, $this->propertiesToArray()));
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function only(string ...$only): array
    {
        return Arr::only($this->toArray(), Arr::map($only, fn ($i) => Str::snake($i)));
    }

    public function except(string ...$except): array
    {
        return Arr::except($this->toArray(), Arr::map($except, fn ($i) => Str::snake($i)));
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->has($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->{$offset};
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset == null) {
            return;
        }

        $property = Str::camel($offset);
        $mapper = static::$propertyMapCache[static::class][$property] ??= $this->extractMapper($property);

        $this->mapProperty($mapper, $property, $value);
    }

    public function __isset($name)
    {
        return $this->has($name);
    }

    public function has(string $field): bool
    {
        try {
            // If the field has not been set this will throw an \Error
            $this->{$field}; // @phpstan-ignore-line
            return true;
        } catch (\Throwable) { // @phpstan-ignore-line
            return false;
        }
    }

    /**
     * Transforms the "raw" array attribute to its correct value for the current resource
     */
    protected function mapProperty(mixed $mapper, string $property, mixed $value)
    {
        if ($mapper == null) {
            return;
        }

        if ($mapper['type'] == 'setter') {
            return $this->{$mapper['setter']}($value);
        }

        $this->{$property} = match ($mapper['type']) {
            'resource' => new $mapper['resource']($value),
            'enum' => $mapper['enum']::from($value),
            'date_immutable' => $value == null ? $value : CarbonImmutable::parse($value),
            'date' => $value == null ? $value : Date::parse($value),
            'float' => $value !== null ? (float)$value : null,
            'int' => $value !== null ? (int)$value : null,
            'none' => $value,
            default => throw new \InvalidArgumentException("Unhandled property mapper type $mapper[type]."),
        };
    }

    /**
     * Maps what each property should do to assign the value correctly
     */
    protected function extractMapper(string $property)
    {
        // If it has a user defined setter use it
        if (method_exists($this, $setter = 'set' . ucfirst($property))) {
            return ['type' => 'setter', 'setter' => $setter];
        }

        // The property does not exists, do nothing
        if (!property_exists($this, $property)) {
            return null;
        }

        $propertyReflection = new \ReflectionProperty($this, $property);
        $propertyType = (string)$propertyReflection->getType()->getName();
        // The property is a nested resource it should cascade
        if (is_a($propertyType, Resource::class, true)) {
            return ['type' => 'resource', 'resource' => $propertyType];
        }
        // immutable date
        if ($propertyType === CarbonImmutable::class) {
            return ['type' => 'date_immutable'];
        }
        // mutable date
        if (is_a($propertyType, DateTimeInterface::class, true)) {
            return ['type' => 'date'];
        }
        // The property is an enum, transform from raw value
        // if the value is no expected the native function will throw an exception
        else if (enum_exists($propertyType)) {
            return ['type' => 'enum', 'enum' => $propertyType];
        }
        else if ($propertyType === 'float') {
            return ['type' => 'float'];
        }
        else if ($propertyType === 'int') {
            return ['type' => 'int'];
        }
        // The property exists but should not be transformed, simply assign it
        else {
            return ['type' => 'none'];
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->{$offset});
    }

    public function __call($name, $arguments)
    {
        if (
            Str::startsWith($name, 'set') &&
            property_exists($this, $property = Str::of($name)->after('set')->lcfirst())
        ) {
            if (($count = count($arguments)) != 1) {
                throw new \InvalidArgumentException("The function $name requires 1 parameter $count given.");
            }

            $this->{$property} = $arguments[0];

            return $this;
        }

        throw new \BadMethodCallException("The method $name does not exists.");
    }
}
