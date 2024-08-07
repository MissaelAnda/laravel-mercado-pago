<?php

namespace MissaelAnda\MercadoPago\Resources;

/**
 * @method static setId(string $id)
 * @method static setAddressLine(string $addressLine)
 * @method static setReference(string $reference)
 * @method static setLatitude(float $latitude)
 * @method static setLongitude(float $longitude)
 * @method static setStreetNumber(string $streetNumber)
 * @method static setStreetName(string $streetName)
 * @method static setCityName(string $cityName)
 * @method static setStateName(string $stateName)
 * @method static setType(string $type)
 * @method static setCity(string $city)
 * @method static setStateId(string $stateId)
 */
class Location extends Resource
{
    public string $id;
    public string $addressLine;
    public string $reference;
    public float $latitude;
    public float $longitude;
    public string $streetNumber;
    public string $streetName;
    public string $cityName;
    public string $stateName;
    public string $type;
    public string $city;
    public string $stateId;
}
