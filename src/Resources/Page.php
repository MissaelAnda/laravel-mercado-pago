<?php

namespace MissaelAnda\MercadoPago\Resources;

/**
 * @method static setTotal(int $total)
 * @method static setOffset(int $offset)
 * @method static setLimit(int $limit)
 */
class Page extends Resource
{
    public int $total;
    public int $offset;
    public int $limit;
}
