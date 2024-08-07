<?php

namespace MissaelAnda\MercadoPago;

use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\User\UserClient;
use MercadoPago\Net\MPDefaultHttpClient;
use MercadoPago\Resources\User;
use MissaelAnda\MercadoPago\Clients\PaymentClient;
use MissaelAnda\MercadoPago\Clients\PointClient;
use MissaelAnda\MercadoPago\Clients\PosClient;
use MissaelAnda\MercadoPago\Clients\StoreClient;
use MissaelAnda\MercadoPago\Contracts\MercadoPagoTenant;
use MissaelAnda\MercadoPago\Exceptions\ApiException;

/**
 * @method StoreClient stores()
 * @method PosClient pos()
 * @method PointClient points()
 *
 * @property-read StoreClient $stores
 * @property-read PosClient $pos
 * @property-read PointClient $points
 */
class Tenant
{
    protected array $services = [];

    protected ?User $user = null;

    public function __construct(protected MercadoPagoTenant $tenant)
    {
        //
    }

    public function me(): User
    {
        if ($this->user == null) {
            try {
                $this->user = (new UserClient())->get(new RequestOptions($this->tenant->accessToken()));
            } catch (\MercadoPago\Exceptions\MPApiException $e) {
                throw new ApiException($e->getApiResponse()->getContent(), $e->getStatusCode());
            }
        }

        return $this->user;
    }

    public function __call($name, $arguments)
    {
        return $this->{$name};
    }

    public function __get($name)
    {
        return $this->services[$name] ??= match ($name) {
            'stores' => new StoreClient($this->tenant),
            'pos' => new PosClient($this->tenant),
            'points' => new PointClient($this->tenant),
            'payments' => new PaymentClient($this->tenant),
            'me' => $this->me(),
            default => throw new \RuntimeException("MercadoPago has no service called $name."),
        };
    }
}
