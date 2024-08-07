<?php

namespace MissaelAnda\MercadoPago\Events;

use Illuminate\Foundation\Events\Dispatchable;

class OAuthCallbackReceived
{
    use Dispatchable;

    public function __construct(
        public string $code,
        public ?string $stateId,
    ) {
        //
    }
}
