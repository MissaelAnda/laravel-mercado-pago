<?php

namespace MissaelAnda\MercadoPago\Resources\Point;

use MissaelAnda\MercadoPago\Resources\Resource;

/**
 * @method static setPrintOnTerminal(bool $printOnTerminal)
 */
class AdditionalInfo extends Resource
{
    public string $externalReference;
    public bool $printOnTerminal;

    public function setExternalReference(?string $reference): static
    {
        // MercadoPago may send and empty string as the reference and if the default middleware
        // ConvertEmptyStringsToNull is active it will transform the string to null
        // we transform it back to an empty string
        $this->externalReference = $reference === null ? '' : $reference;

        return $this;
    }
}
