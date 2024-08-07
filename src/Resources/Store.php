<?php

namespace MissaelAnda\MercadoPago\Resources;

use Carbon\CarbonImmutable;

/**
 * @method static setId(int $id)
 * @method static setName(string $name)
 * @method static setExternalId(string $externalId)
 * @method static setBusinessHours(array $businessHours)
 * @method static setLocation(Location $location)
 * @method static setDateCreation(CarbonImmutable $dateCreation)
 */
class Store extends Resource
{
    public int $id;
    public string $name;
    public string $externalId;
    public array $businessHours;
    public Location $location;
    public CarbonImmutable $dateCreation;
}
