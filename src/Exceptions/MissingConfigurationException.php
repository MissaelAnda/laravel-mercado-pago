<?php

namespace MissaelAnda\MercadoPago\Exceptions;

class MissingConfigurationException extends \Exception
{
    public static function make(string $config): self
    {
        return new self("Missing MercadoPago $config configuration.");
    }
}
