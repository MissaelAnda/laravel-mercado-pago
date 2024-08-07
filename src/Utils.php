<?php

namespace MissaelAnda\MercadoPago;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class Utils
{
    public static function toRequestArray(array $data): array
    {
        return Arr::mapWithKeys($data, fn ($value, $key) => [Str::snake($key) => $value]);
    }

    public static function filteredRequestArray(array $data): array
    {
        return array_filter(static::toRequestArray($data));
    }
}
