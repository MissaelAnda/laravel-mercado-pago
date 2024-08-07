<?php

namespace MissaelAnda\MercadoPago\Events;

use Illuminate\Foundation\Events\Dispatchable;

class WebhookReceived
{
    use Dispatchable;

    /**
     * @param  array<string,mixed>  $query
     * @param  array<string,mixed>  $payload
     */
    public function __construct(
        public ?string $requestId,
        public ?string $signature,
        public array $query,
        public array $payload,
    ) {
        //
    }
}
