<?php

namespace MissaelAnda\MercadoPago\Http\Controllers;

use Illuminate\Http\Request;
use MissaelAnda\MercadoPago\Events\WebhookData;
use MissaelAnda\MercadoPago\Events\WebhookEvent;
use MissaelAnda\MercadoPago\Events\PointIntegrationEvent;
use MissaelAnda\MercadoPago\Events\WebhookReceived;
use MissaelAnda\MercadoPago\Resources\Point\PaymentIntent;

class WebhookController
{
    public function __invoke(Request $request)
    {
        WebhookReceived::dispatch(
            $requestId = $request->header('x-request-id'),
            $request->header('x-signature'),
            $request->query->all(),
            $request->request->all(),
        );

        event(match ($request->input('type')) {
            'point_integration_wh' => new PointIntegrationEvent($requestId, new PaymentIntent($request->request->all())),
            default => new WebhookEvent($requestId, new WebhookData($request->request->all())),
        });

        return response()->json();
    }
}
