<?php

namespace MissaelAnda\MercadoPago\Resources\Point;

use MissaelAnda\MercadoPago\Resources\Resource;

/**
 * @method static setId(string $id)
 * @method static setInstallments(int $installments)
 * @method static setInstallmentsCost(string $installmentsCost)
 * @method static setType(string $type)
 * @method static setState(string $state)
 */
class Payment extends Resource
{
    public int $id;
    public int $installments;
    public string $installmentsCost;
    public string $type;
    public string $state;
}
