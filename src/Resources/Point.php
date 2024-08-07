<?php

namespace MissaelAnda\MercadoPago\Resources;

use MissaelAnda\MercadoPago\Resources\Point\OperatingMode;

/**
 * @method static setId(string $id)
 * @method static setPosId(int $posId)
 * @method static setStoreId(int $storeId)
 * @method static setExternalPosId(string $externalPosId)
 * @method static setOperatingMode(OperatingMode $operatingMode)
 */
class Point extends Resource
{
    public string $id;
    public int $posId;
    public int $storeId;
    public string $externalPosId;
    public OperatingMode $operatingMode;
}
