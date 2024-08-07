<?php

namespace MissaelAnda\MercadoPago\Clients;

use MissaelAnda\MercadoPago\Contracts\MercadoPagoTenant;
use MissaelAnda\MercadoPago\Exceptions\ApiException;
use BadMethodCallException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use MissaelAnda\MercadoPago\Resources\Resource;

/**
 * @method array get(string|null $path = null, array $data = [])
 * @method array post(string|null $path = null, array $data = [])
 * @method array put(string|null $path = null, array $data = [])
 * @method array patch(string|null $path = null, array $data = [])
 * @method array delete(string|null $path = null, array $data = [])
 */
abstract class Client
{
    public const API_BASE_URL = 'https://api.mercadopago.com';

    protected ?string $accessToken = null;

    protected ?string $lastIdempotency = null;

    protected ?string $idempotency = null;

    public function __construct(protected ?MercadoPagoTenant $tenant)
    {
        $this->accessToken = $tenant?->accessToken();
    }

    public static function replaceUriParams(string $uri, string|int|float ...$params): string
    {
        return preg_replace(['/\{[^\}]+\}/'], $params, $uri);
    }

    public function buildApiUrl(?string $path = null): string
    {
        return (string)Str::of(self::API_BASE_URL)
            ->append($path ? Str::start($path, '/') : $path)
            ->when(fn ($str) => $str->endsWith('/'))->substr(0, -1);
    }

    public function withAccessToken(string $token): static
    {
        $this->accessToken = $token;

        return $this;
    }

    public function withIdempotency(string &$idempotency): static
    {
        $idempotency = $idempotency ?: Str::ulid();
        $this->idempotency = clone $idempotency;

        return $this;
    }

    public function getLastIdempotency(): ?string
    {
        return $this->lastIdempotency;
    }

    protected function request(string $method, ?string $path = null, array $data = []): array
    {
        $response = Http::acceptJson()
            ->when(isset($this->accessToken))->withToken($this->accessToken)
            ->when(isset($this->idempotency))->withHeader('X-Idempotency-Key', $this->idempotency)
            ->{strtolower($method)}($this->buildApiUrl($path), $data);

        $this->lastIdempotency = $this->idempotency;
        $this->idempotency = null;

        if (!$response->successful()) {
            throw new ApiException($response->json() ?? [], $response->status());
        }

        return $response->json();
    }

    protected static function validate(mixed $instance, string $property): mixed
    {
        if (!$instance instanceof Resource) {
            return $instance;
        }

        if (!isset($instance->{$property})) {
            $class = class_basename($instance);
            $property = Str::snake($property, ' ');
            throw new \InvalidArgumentException("The $class $property is required.");
        }

        return $instance->{$property};
    }

    /**
     * @template T
     * @param  class-string<T>  $resource
     * @return ?T
     */
    protected function findResource(string $resource, string $url, array $params = []): mixed
    {
        try {
            $data = $this->get($url, $params);
        } catch (ApiException $e) {
            if ($e->getCode() === 404) {
                return null;
            }

            throw $e;
        }

        return new $resource($data);
    }

    /**
     * @throws ApiException
     */
    public function __call($name, $arguments): mixed
    {
        if (in_array($func = strtolower($name), ['get', 'post', 'put', 'patch', 'delete'])) {
            return $this->request($func, ...$arguments);
        }

        throw new BadMethodCallException("The function $name does not exists.");
    }
}
