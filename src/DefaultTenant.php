<?php

namespace MissaelAnda\MercadoPago;

use MissaelAnda\MercadoPago\Contracts\MercadoPagoTenant;

class DefaultTenant implements MercadoPagoTenant
{
    public function __construct(
        protected ?string $id,
        protected string $accessToken,
        protected ?string $refreshToken,
        protected string $publicKey,
    ) {
        //
    }

    public function id(): string
    {
        return $this->id;
    }

    public function accessToken(): string
    {
        return $this->accessToken;
    }

    public function refreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function publicKey(): string
    {
        return $this->publicKey;
    }
}
