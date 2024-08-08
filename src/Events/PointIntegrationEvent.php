<?php

namespace MissaelAnda\MercadoPago\Events;

use Illuminate\Foundation\Events\Dispatchable;
use MissaelAnda\MercadoPago\Resources\Point\PaymentIntent;

class PointIntegrationEvent
{
    use Dispatchable;

    public function __construct(
        public ?string $requestId,
        public PaymentIntent $intent
    ) {
        //
    }
}
