<?php

namespace MissaelAnda\MercadoPago;

use MissaelAnda\MercadoPago\Clients\StoreClient;
use MissaelAnda\MercadoPago\Clients\OAuthClient;
use MissaelAnda\MercadoPago\Clients\PosClient;
use MissaelAnda\MercadoPago\Contracts\MercadoPagoTenant;
use Illuminate\Contracts\Config\Repository;
use MissaelAnda\MercadoPago\Clients\PaymentClient;
use MissaelAnda\MercadoPago\Clients\PointClient;

/**
 * @method OAuthClient oauth()
 * @method StoreClient stores(MercadoPagoTenant|null $tenant = null)
 * @method PosClient pos(MercadoPagoTenant|null $tenant = null)
 * @method PointClient points(MercadoPagoTenant|null $tenant = null)
 * @method PaymentClient payments(MercadoPagoTenant|null $tenant = null)
 * @method \MercadoPago\Resources\User me(MercadoPagoTenant|null $tenant = null)
 */
class MercadoPago
{
    protected ?OAuthClient $oauth = null;

    /**
     * Service clients cache
     */
    protected array $tenants = [];

    public function __construct(protected Repository $config)
    {
        //
    }

    public function tenant(?MercadoPagoTenant $tenant = null): Tenant
    {
        return $this->tenants[$tenant?->id() ?? 'default'] ??=
            new Tenant($tenant ?? app(DefaultTenant::class));
    }

    public function default(): Tenant
    {
        return $this->tenant();
    }

    public function __call($name, $arguments)
    {
        if ($name == 'oauth') {
            return $this->oauth ??= new OAuthClient(
                $this->config->get('mercado-pago.app_id'),
                $this->config->get('mercado-pago.app_secret'),
                route($this->config->get('mercado-pago.oauth.route_name')),
            );
        }

        return $this->tenant(...$arguments)->{$name};
    }
}
