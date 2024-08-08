<?php

namespace MissaelAnda\MercadoPago\Events;

use Illuminate\Foundation\Events\Dispatchable;
use MissaelAnda\MercadoPago\Resources\Resource;

class WebhookEvent extends Resource
{
    use Dispatchable;

    public function __construct(
        public ?string $requestId,
        public WebhookData $webhook,
    ) {
        //
    }
}
