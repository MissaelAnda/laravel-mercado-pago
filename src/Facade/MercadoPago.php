<?php

namespace MissaelAnda\MercadoPago\Facade;

use MissaelAnda\MercadoPago\Contracts\MercadoPagoTenant;
use MissaelAnda\MercadoPago\MercadoPago as MercadoPagoMercadoPago;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \MissaelAnda\MercadoPago\Tenant default()
 * @method static \MissaelAnda\MercadoPago\Tenant tenant(MercadoPagoTenant|null $tenant = null)
 * @method static \MissaelAnda\MercadoPago\Clients\OAuthClient oauth()
 * @method static \MissaelAnda\MercadoPago\Clients\StoreClient stores(MercadoPagoTenant|null $tenant = null)
 * @method static \MissaelAnda\MercadoPago\Clients\PosClient pos(MercadoPagoTenant|null $tenant = null)
 * @method static \MissaelAnda\MercadoPago\Clients\PointClient points(MercadoPagoTenant|null $tenant = null)
 * @method static \MissaelAnda\MercadoPAgo\Clients\PaymentClient payments(MercadoPagoTenant|null $tenant = null)
 * @method static \MercadoPago\Resources\User me(MercadoPagoTenant|null $tenant = null)
 */
class MercadoPago extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MercadoPagoMercadoPago::class;
    }
}
