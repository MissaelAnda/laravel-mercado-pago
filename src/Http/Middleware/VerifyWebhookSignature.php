<?php

namespace MissaelAnda\MercadoPago\Http\Middleware;

use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use MissaelAnda\MercadoPago\Exceptions\MissingConfigurationException;

class VerifyWebhookSignature
{
    public function __construct(protected Repository $config)
    {
        //
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->verifySignature($request);

        return $next($request);
    }

    protected function verifySignature(Request $request)
    {
        if (empty($signature = $request->header('x-signature'))) {
            $this->fail('Missing signature header.');
        }

        if (empty($requestId = $request->header('x-request-id'))) {
            $this->fail('Missing request id header.');
        }

        if (empty($eventId = $request->input('data_id'))) {
            $this->fail('Missing event id.');
        }

        $parts = $this->parseSignature($signature);

        if (!isset($parts['ts']) || !isset($parts['v1'])) {
            $this->fail();
        }

        if (!$this->validateWebhookSignature($eventId, $requestId, $parts['ts'], $parts['v1'])) {
            $this->fail();
        }
    }

    protected function validateWebhookSignature(
        string $eventId,
        string $requestId,
        string $ts,
        string $signature,
    ): bool {
        $manifest = "id:$eventId;request-id:$requestId;ts:$ts;";
        return hash_equals(hash_hmac('sha256', $manifest, $this->webhookSecret()), $signature);
    }

    protected function webhookSecret(): string
    {
        if (empty($secret = $this->config->get('mercado-pago.webhook.secret'))) {
            throw MissingConfigurationException::make('webhook.secret');
        }

        return $secret;
    }

    protected function parseSignature(string $signature): array
    {
        $parts = [];

        foreach (explode(',', $signature) as $part) {
            $pairs = explode('=', $part, 2);

            if (count($pairs) != 2) {
                continue;
            }

            $parts[$pairs[0]] = $pairs[1];
        }

        return $parts;
    }

    /**
     * @throws AccessDeniedHttpException
     *
     * @return never
     */
    protected function fail(?string $message = null): void
    {
        throw new AccessDeniedHttpException(__($message ?: 'Invalid signature.'));
    }
}
