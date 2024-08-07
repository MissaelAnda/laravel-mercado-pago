<?php

namespace MissaelAnda\MercadoPago\Contracts;

interface MercadoPagoTenant
{
    function id(): string;
    function accessToken(): string;
    function refreshToken(): ?string;
    function publicKey(): string;
}
